<template>
    <div>
        <game></game>
    </div>
</template>

<script>
import {mapGetters, mapActions} from "vuex";
import Game from "./Game";

export default {
    name: "app",
    components: {Game},
    props: ["loggedUser"],
    computed: mapGetters(["user"]),
    methods: mapActions(["getUser", "setUser"]),
    created() {
        this.setUser(JSON.parse(this.loggedUser));
    }
}
</script>
