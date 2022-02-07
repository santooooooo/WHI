<template>
    <v-container>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">I'm an example component.</div>
		    <h1>Hello</h1>
		    <h1>Hello</h1>
		    <h1>Hello</h1>
		    <h1>Hello</h1>
		    <h1>Hello</h1>
		    <h1>Hello</h1>
		    <h1>Hello</h1>
		    <h1>Hello</h1>
                </div>
            </div>
        </div>
    </v-container>
</template>

<script>
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
