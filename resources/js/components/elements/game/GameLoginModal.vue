<template>
    <div>
        <transition name="modal">
            <div v-if="showModal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog" role="document">
                            <div class="modal-login-content">
                                <div class="modal-body">
                                    <div class="container d-flex flex-column justify-content-center align-items-center">
                                        <wax-login-button @login="login"/>
                                        <anchor-login-button/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <button @click="showModal = true">Click</button>
    </div>
</template>

<script>
import AnchorLoginButton from "../../api/AnchorLoginButton.vue";
import WaxLoginButton from "../../api/WaxLoginButton.vue";

export default {
    components: {AnchorLoginButton, WaxLoginButton},
    props: {

    },
    name: "GameLoginModal",
    data: function(){
        return {
            showModal: true,
        }
    },
    created: function(){
        console.log(user);
        this.showModal = false;
    },
    methods: {
        login (data) {
            console.log('child component said login', data)
        }
    },
    computed: {},
};
</script>

<style scoped>
</style>
