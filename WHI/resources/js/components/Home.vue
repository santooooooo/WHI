<template>
    <v-container>
        <v-avatar color="primary" size="50" rounded
            ><span class="white--text text-h5">36</span></v-avatar
        >
        <v-app-bar elevation="100" color="blue" app>Jamboo</v-app-bar>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">I'm an example component.</div>
                </div>
            </div>
        </div>
    </v-container>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            test: "",
        };
    },
    mounted() {
        console.log("Component mounted.");
    },
    computed: {
        testFunc() {
            console.log(this.$store.state.test);
            this.$store.getters["testLog"];
        },
    },
};
</script>
