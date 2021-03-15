const app = new Vue({
    el: ('#durakapp'),
    data: {
        settings: [],
        playersIdle: [],
        games: [],
        playersPlaying: [],
        currentlyPlayingClass: "currentlyPlaying",
    },
    computed: {
        gamesThisSession() {
            let filteredBySession = this.games.filter(
                game => game.session_id === this.settings.id
            )
            let lastGames = [];
            for (let i = 0; i < filteredBySession.length; i++) {

                let loser = this.playersIdle.find(player => player.id === filteredBySession[i].loser)
                let loser2 = this.playersIdle.find(player => player.id === filteredBySession[i].loser_2)
                //Add loser
                lastGames[i] = {
                    loser: loser.name,
                    loserColor: loser.color,
                    loser_2: loser2 ? loser2.name : -1,
                    loser2Color: loser2 ? loser2.color : "#212529",
                    players: filteredBySession[i].players,
                    modified: filteredBySession[i].modified,
                    session_id: this.settings.currentSessionId
                }
            }
            //Return games reversed, so last game is displayed first (you could also do this in vue-html i think)
            return lastGames.reverse();
        }
    },
    created() {
        //Get settings
        axios.get('/src/durakapp/public/api/settings/read.php')
            .then(function (response) {
                app.settings = response.data.records[0];
            })
            .catch(function (error) {
                console.error(error)
            });

        //Get player-data
        axios.get('/src/durakapp/public/api/player/read.php')
            .then(function (response) {
                app.playersIdle = response.data.records;
                app.playersPlaying = response.data.records.filter(
                    player => player.currentlyPlaying === true
                )
            })
            .catch(function (error) {
                console.error(error)
            });

        //Get games-data
        this.getGameData()
    },
    methods: {
        startDrag(evt, player) {
            //TODO: different methods for mobile and desktop - cause that shits not working on mobile
            evt.dataTransfer.dropEffect = 'move'
            evt.dataTransfer.effectAllowed = 'move'
            evt.dataTransfer.setData('playerID', player.id)
        },
        onDrop(evt, isPlayingList) {
            const playerID = evt.dataTransfer.getData('playerID')
            const player = this.playersIdle.find(player => player.id === playerID)
            if (isPlayingList && !player.currentlyPlaying) {
                this.playersPlaying.push(player);
                player.currentlyPlaying = 1;
                this.updatePlayer(player)
            } else if (!isPlayingList) {
                const index = this.playersPlaying.indexOf(player);
                if (index > -1) {
                    player.currentlyPlaying = 0;
                    this.updatePlayer(player)
                    this.playersPlaying.splice(index, 1);
                }
            }
            player.currentlyPlaying = isPlayingList
        },
        submitDraw(evt, player) {
            let playerID = evt.dataTransfer.getData('playerID')
            let playerDragged = this.playersIdle.find(player => player.id === playerID)
            if (playerDragged.currentlyPlaying && playerDragged !== player) {
                this.createGame(playerDragged.id, player.id);
                //Update player-data
                let players = this.playersPlaying;
                for (let i = 0; i < players.length; i++) {
                    if (players[i] === playerDragged || players[i] === player) {
                        players[i].draws++;
                    }
                    players[i].gamescount++
                    this.updatePlayer(players[i])
                }
            }
        },
        submitLose(loser) {
            //Can't play alone :<
            if (this.playersPlaying.length >= 2) {
                //Save game to db
                this.createGame(loser.id, -1);
                //Update player-data
                let players = this.playersPlaying;
                for (let i = 0; i < players.length; i++) {
                    if (players[i] === loser) {
                        players[i].losses++;
                    }
                    players[i].gamescount++
                    this.updatePlayer(players[i])
                }
            } else {
                //TODO: Add alert-message
            }

        },
        getPlayersPlaying() {
            let players = ''
            for (let i = 0; i < this.playersPlaying.length; i++) {
                i === this.playersPlaying.length - 1
                    ? players += this.playersPlaying[i].id
                    : players += this.playersPlaying[i].id + ','
            }
            return players
        },
        createGame(loserId, loser_2Id) {
            let players = this.getPlayersPlaying();
            axios.post('/src/durakapp/public/api/game/create.php', {
                loser: loserId,
                loser_2: loser_2Id,
                players: players,
                session_id: this.settings.id
            })
                .then(function (response) {
                    app.getGameData()
                    return true
                })
                .catch(function (error) {
                    console.error(error)
                });
        },
        updatePlayer(player) {
            axios.post('/src/durakapp/public/api/player/update.php', player)
                .then(function (response) {
                    console.log(response.data.message)
                })
                .catch(function (error) {
                    console.error(error)
                });
        },
        getGameData() {
            //Get games-data
            axios.get('/src/durakapp/public/api/game/read.php')
                .then(function (response) {
                    app.games = response.data.records;
                    app.setSettings();
                })
                .catch(function (error) {
                    console.error(error)
                });
        },
        setSettings() {
            //New Session if last game is more than 24h away
            let lastGame = app.games[app.games.length - 1];
            let lastGameDate = Math.round(new Date(Date.parse(lastGame.modified)).getTime() / 1000)
            let currentDate = Math.round(new Date().getTime() / 1000);
            if ((currentDate - lastGameDate) / 24 / 60 / 60 >= 1) {
                axios.post('/src/durakapp/public/api/settings/update.php', {
                    id: ++this.settings.id
                })
                    .then(function (response) {
                        console.log(response.data.message)
                    })
                    .catch(function (error) {
                        console.error(error)
                    });
            }
        }
    }
})