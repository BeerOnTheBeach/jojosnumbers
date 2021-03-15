<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bearbeiten (Admin-Mode)</title>

    <link href="../../src/assets/lib/bootstrap/css/bootstrap.css" rel="stylesheet"/>
    <link href="../../src/assets/common/css/edit.css" rel="stylesheet"/>
    <script src="../../src/assets/lib/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../../src/assets/lib/vue/vue.js"></script>
    <script src="../../src/assets/lib/vue/axois.js"></script>
</head>
<body>
<div id="edit" class="container">
    <nav>
        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                    type="button" role="tab" aria-controls="nav-home" aria-selected="true">Spieler hinzufügen
            </button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                    type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Spieler entfernen
            </button>
            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                    type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Spieler bearbeiten
            </button>
        </div>
    </nav>
    <!--Spieler hinzufügen Content-->
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="player-container">
                <template v-for="player in players">
                    <div class="player player-add">
                        <svg xmlns="http://www.w3.org/2000/svg" width="75" height="75" :fill="player.color" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                        </svg>
                        <div class="caption text-center">{{player.name}}</div>
                    </div>
                </template>
            </div>
            <form v-on:submit.prevent="createPlayer">
                <div id="player-add" class="form-container row g-3">
                    <div class="col-md">
                        <label for="add-name" class="form-label">Name</label>
                        <input v-model="form.name" type="text" class="form-control" id="add-name" placeholder="Name"
                               required>
                    </div>
                    <div class="col-md">
                        <label for="colorInput" class="form-label">Spielerfarbe</label>
                        <input v-model="form.color" type="color" class="form-control" id="colorInput"
                               value="#198754"
                               title="Spielerfarbe wählen">
                    </div>
                    <div class="col-md">
                        <label for="games-lost" class="form-label">Spiele verloren</label>
                        <input v-model="form.losses" id="games-lost" class="form-control" value="0" type="text"
                               placeholder="0"
                               aria-label="Spiele verloren" disabled>
                    </div>
                    <div class="col-md">
                        <label for="games-draw" class="form-label">Spiele unentschieden</label>
                        <input v-model="form.draws" id="games-draw" class="form-control" value="0" type="text"
                               placeholder="0"
                               aria-label="Spiele unentschieden" disabled>
                    </div>
                    <div class="col-md">
                        <label for="games-count" class="form-label">Spiele gesamt</label>
                        <input v-model="form.gamescount" id="games-count" class="form-control" value="0" type="text"
                               placeholder="0"
                               aria-label="Spiele gesamt" disabled>
                    </div>
                    <div class="col-md">
                        <label for="player-elo" class="form-label">Spieler-Elo</label>
                        <input v-model="form.elo" class="form-control" type="text" value="1500" placeholder="1500"
                               aria-label="Elo" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary form-control">Bestätigen</button>
                </div>
                <div class="alert alert-success alert-message" v-if="alert.playerCreate.success.show">
                    {{alert.playerCreate.success.message}}
                </div>
                <div class="alert alert-danger alert-message" v-if="alert.playerCreate.failed.show">
                    {{alert.playerCreate.failed.message}}
                </div>
            </form>
        </div>
        <!--Spieler entfernen Content-->
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="player-container">
                <template v-for="player in players">
                    <div @click="showProfile(player)" :class="{ active: player.isActive }" class="player">
                        <svg xmlns="http://www.w3.org/2000/svg" width="75" height="75" :fill="player.color" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                        </svg>
                        <div class="caption text-center">{{player.name}}</div>
                    </div>
                </template>
            </div>
            <form v-if="profile.show" v-on:submit.prevent="deletePlayer">
                <div id="player-add" class="form-container row g-3">
                    <div class="col-md">
                        <label for="add-name" class="form-label">Name</label>
                        <input :value="profile.player.name" type="text" class="form-control" id="add-name"
                               placeholder="Name"
                               disabled>
                    </div>
                    <div class="col-md">
                        <label for="colorInput" class="form-label">Spielerfarbe</label>
                        <input :value="profile.player.color" type="color" class="form-control" id="colorInput" disabled>
                    </div>
                    <div class="col-md">
                        <label for="games-lost" class="form-label">Spiele verloren</label>
                        <input :value="profile.player.losses" id="games-lost" class="form-control"
                               type="text"
                               placeholder="0"
                               aria-label="Spiele verloren" disabled>
                    </div>
                    <div class="col-md">
                        <label for="games-draw" class="form-label">Spiele unentschieden</label>
                        <input :value="profile.player.draws" id="games-draw" class="form-control"
                               type="text"
                               placeholder="0"
                               aria-label="Spiele unentschieden" disabled>
                    </div>
                    <div class="col-md">
                        <label for="games-count" class="form-label">Spiele gesamt</label>
                        <input v-model="profile.player.gamescount" id="games-count" class="form-control" value="0" type="text"
                               placeholder="0"
                               aria-label="Spiele gesamt" disabled>
                    </div>
                    <div class="col-md">
                        <label for="player-elo" class="form-label">Spieler-Elo</label>
                        <input :value="profile.player.elo" class="form-control" type="text"
                               placeholder="1500"
                               aria-label="Elo" disabled>
                    </div>
                    <button type="submit" class="btn btn-danger form-control">Bestätigen</button>
                </div>
                <div class="alert alert-success alert-message" v-if="alert.playerDelete.success.show">
                    {{alert.playerDelete.success.message}}
                </div>
                <div class="alert alert-danger alert-message" v-if="alert.playerDelete.failed.show">
                    {{alert.playerDelete.failed.message}}
                </div>
            </form>
        </div>
        <!--Spieler bearbeiten Content-->
        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <div class="player-container">
                <template v-for="player in players">
                    <div @click="showProfile(player)" :class="{ active: player.isActive }" class="player">
                        <svg xmlns="http://www.w3.org/2000/svg" width="75" height="75" :fill="player.color" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                        </svg>
                        <div class="caption text-center">{{player.name}}</div>
                    </div>
                </template>
            </div>
            <form v-if="profile.show" v-on:submit.prevent="updatePlayer">
                <div id="player-add" class="form-container row g-3">
                    <div class="col-md">
                        <label for="add-name" class="form-label">Name</label>
                        <input v-model="profile.player.name" type="text" class="form-control" id="add-name"
                               placeholder="Name"
                               required>
                    </div>
                    <div class="col-md">
                        <label for="colorInput" class="form-label">Spielerfarbe</label>
                        <input v-model="profile.player.color" type="color" class="form-control" id="colorInput"
                               value="#198754"
                               title="Spielerfarbe wählen">
                    </div>
                    <div class="col-md">
                        <label for="games-lost" class="form-label">Spiele verloren</label>
                        <input v-model="profile.player.losses" id="games-lost" class="form-control"
                               type="text"
                               placeholder="0"
                               aria-label="Spiele verloren" disabled>
                    </div>
                    <div class="col-md">
                        <label for="games-draw" class="form-label">Spiele unentschieden</label>
                        <input v-model="profile.player.draws" id="games-draw" class="form-control"
                               type="text"
                               placeholder="0"
                               aria-label="Spiele unentschieden" disabled>
                    </div>
                    <div class="col-md">
                        <label for="games-count" class="form-label">Spiele gesamt</label>
                        <input :value="profile.player.gamescount" id="games-count" class="form-control"
                               type="text"
                               placeholder="0"
                               aria-label="Spiele gesamt" disabled>
                    </div>
                    <div class="col-md">
                        <label for="player-elo" class="form-label">Spieler-Elo</label>
                        <input v-model="profile.player.elo" class="form-control" type="text"
                               placeholder="1500"
                               aria-label="Elo" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary form-control">Bestätigen</button>
                </div>
                <div class="alert alert-success alert-message" v-if="alert.playerUpdate.success.show">
                    {{alert.playerUpdate.success.message}}
                </div>
                <div class="alert alert-danger alert-message" v-if="alert.playerUpdate.failed.show">
                    {{alert.playerUpdate.failed.message}}
                </div>
        </div>
    </div>
</div>
<script src="edit.js"></script>
</body>
</html>