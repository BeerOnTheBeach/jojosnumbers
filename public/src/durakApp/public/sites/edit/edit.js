const edit = new Vue({
    el: ('#edit'),
    data: {
        players: {
            isActive: false
        },
        form: {
            name: '',
            losses: '0',
            draws: '0',
            elo: '1500',
            color: '#198754',
            gamescount: '0',
            currentlyPlaying: '0'
        },
        profile: {
            player: '',
            show: false
        },
        alert: {
            playerCreate: {
                success: {
                    message: "",
                    show: false
                },
                failed: {
                    message: "",
                    show: false
                }
            },
            playerUpdate: {
                success: {
                    message: "",
                    show: false
                },
                failed: {
                    message: "",
                    show: false
                }
            },
            playerDelete: {
                success: {
                    message: "",
                    show: false
                },
                failed: {
                    message: "",
                    show: false
                }
            }
        }
    },
    created() {
        //Get player-data
        axios.get('../../api/player/read.php')
            .then(function (response) {
                //Init players
                edit.players = response.data.records;
                //Add active-class to every player after api-call so it wont be overwritten
                for (let i = 0; i < edit.players.length; i++) {
                    edit.$set(edit.players[i], 'isActive', false)
                }
            })
            .catch(function (error) {
                console.error(error)
            });
    },
    methods: {
        createPlayer() {
            //Hide previous alerts
            this.hideAlerts();

            //Check if player with that name already exists
            if (!this.playerAlreadyExists(this.form.name)) {
                axios.post('../../api/player/create.php', this.form)
                    .then((res) => {
                        this.alert.playerCreate.success.message = "Spieler '" + this.form.name + "' wurde hinzugefügt."
                        this.alert.playerCreate.success.show = true
                        //Push player to players-array, so we dont have to call the api again (would work too)
                        this.players.push(this.form)
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            } else {
                this.alert.playerCreate.failed.message = "Spieler mit Name '" + this.form.name + "' existiert bereits."
                this.alert.playerCreate.failed.show = true
            }
        },
        updatePlayer() {
            //Hide previous alerts
            this.hideAlerts();

            //Update player
            axios.post('../../api/player/update.php', this.profile.player)
                .then((res) => {
                    this.alert.playerUpdate.success.message = "Spieler '" + this.profile.player.name + "' wurde erfolgreich gespeichert."
                    this.alert.playerUpdate.success.show = true
                })
                .catch((error) => {
                    console.log(error)
                });
        },
        deletePlayer() {
            //Hide previous alerts
            this.hideAlerts();

            //Check if this player has already played at least on game.
            if(this.profile.player.losses === '0' && this.profile.player.draws === '0') {
                //Delete player
                axios.post('../../api/player/delete.php', this.profile.player.id)
                    .then((res) => {
                        console.log(res)
                        this.alert.playerDelete.success.message = "Spieler '" + this.profile.player.name + "' wurde gelöscht"
                        this.alert.playerDelete.success.show = true

                        //Splice from players, so it updates in the frontend without the need of another api-call
                        let index = this.players.indexOf(this.profile.player);
                        this.players.splice(index, 1);
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            } else {
                this.alert.playerDelete.failed.message = "Spieler '" + this.profile.player.name + "' konnte nicht gelöscht werden."
                this.alert.playerDelete.failed.show = true
            }
        },
        playerAlreadyExists(formName) {
            for (let i = 0; i < this.players.length; i++) {
                if (formName === this.players[i].name) {
                    return true;
                }
            }
            return false;
        },
        showProfile(player) {
            //set active class for profile selected
            for (let i = 0; i < this.players.length; i++) {
                console.log(this.players[i].isActive)
                this.players[i].isActive = false;
            }
            player.isActive = true;

            //set player to be shown
            this.profile.player = player;
            this.profile.show = true;
        },
        hideAlerts() {
            this.alert.playerCreate.success.show = false
            this.alert.playerCreate.failed.show = false
            this.alert.playerDelete.success.show = false
            this.alert.playerDelete.failed.show = false
            this.alert.playerUpdate.success.show = false
        }
    }
})