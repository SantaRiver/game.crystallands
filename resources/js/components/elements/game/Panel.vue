<template>
    <div class="container-fluid container-xl px-0" style="max-width: 95vw">
        <div class="row">
            <div class="col-2 d-flex align-self-center position-relative">
                <img class="w-100" v-bind:src="'./images/interface/health.png'" alt="health">
                <div class="w-100 h-100 position-absolute d-flex justify-content-center align-items-center">
                    <p class="stat mb-0">123</p>
                </div>
            </div>
            <div class="col-2 d-flex align-self-center position-relative">
                <img class="w-100"  alt="hunger">
                <div class="w-100 h-100 position-absolute d-flex justify-content-center align-items-center">
                    <p class="stat mb-0">123</p>
                </div>
            </div>
            <div class="col-4 position-relative">

                <img class="w-100"  alt="wallet-btn">

            </div>
            <div class="col-2 d-flex align-self-center position-relative">
                <img class="w-100"  alt="energy">
                <div class="w-100 h-100 position-absolute d-flex justify-content-center align-items-center">
                    <p class="stat mb-0">123</p>
                </div>
            </div>
            <div class="col-2 d-flex align-self-center position-relative">
                <img class="w-100"  alt="money">
                <div class="w-100 h-100 position-absolute d-flex justify-content-center align-items-center">
                    <p class="stat mb-0">123</p>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Panel",
    computed: {
        health(){
            return require('/images/interface/health.png')
        }
    }
}
</script>

<style scoped>

</style>
