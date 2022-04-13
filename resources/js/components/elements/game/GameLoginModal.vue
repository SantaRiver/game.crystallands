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
                                        <wax-login-button/>
                                        <anchor-login-button/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import AnchorLoginButton from "../../api/AnchorLoginButton.vue";
import WaxLoginButton from "../../api/WaxLoginButton.vue";

export default {
    components: {AnchorLoginButton, WaxLoginButton},
    name: "GameLoginModal",
    props: ["showModal"],
};
</script>

<style scoped>
</style>
