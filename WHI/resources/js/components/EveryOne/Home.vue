<template>
    <v-container>
        <div class="justify-center">
            <div class="home">
                <h1>自己PRでお困りではありませんか？</h1>
                <h3>
                    自己PRのページをインターネットで作ろうとは思うもののデザインを考えるのが面倒くさい、そもそもどうやって作ればよいのかわからない、様々なSNSに自分の情報が分散してしまっている、従来のSNSでは満足できない......。WHI?はこれらの悩みを解決し、あなたの自己PRをお助けします。
                </h3>
                <div class="pa-7 text-center ml-auto mr-auto three-point">
                    <h2
                        class="white black--text rounded-pill pa-2 ml-auto mr-auto mb-5"
                    >
                        WHI?の3つの特徴
                    </h2>

                    <v-card
                        class="d-flex justify-space-around ml-auto mr-auto pa-5 mb-5"
                    >
                        <v-icon class="black--text pr-0" size="6.5rem"
                            >mdi-file-account</v-icon
                        >
                        <v-card-title
                            >テキストを入力するだけで自己PRページが手軽に作成可能</v-card-title
                        >
                    </v-card>

                    <v-card
                        class="d-flex justify-space-around ml-auto mr-auto pa-5 mb-5"
                    >
                        <div class="d-flex">
                            <v-icon class="black--text pr-0" size="3rem"
                                >mdi-application-edit-outline</v-icon
                            >
                            <v-icon class="black--text px-0.5" size="2.5rem"
                                >mdi-transit-connection-variant</v-icon
                            >
                            <div style="display: grid">
                                <v-icon class="black--text pr-0" size="2rem"
                                    >mdi-post</v-icon
                                >
                                <v-icon class="black--text pr-0" size="2rem"
                                    >mdi-twitter</v-icon
                                >
                                <v-icon class="black--text pr-0" size="2rem"
                                    >mdi-link</v-icon
                                >
                            </div>
                        </div>
                        <v-card-title
                            >ブログの作成とURLを通じた外部からの情報の表示が可能</v-card-title
                        >
                    </v-card>

                    <v-card
                        class="d-flex justify-space-around ml-auto mr-auto pa-5 mb-5"
                    >
                        <v-icon class="black--text pr-0" size="6.5rem"
                            >mdi-currency-usd-off</v-icon
                        >
                        <v-card-title
                            >利用料金は利用期間に関わらず、ずっーと無料！</v-card-title
                        >
                    </v-card>
                    <v-btn color="orange" class="text-decoration-none white--text">利用例を見る</v-btn>
                    <v-btn color="green"
                        ><router-link
                            to="signup"
                            class="text-decoration-none white--text"
                            >始める</router-link
                        ></v-btn
                    >
                </div>
            </div>
        </div>
    </v-container>
</template>

<script>
export default {
    data() {
        return {};
    },
    mounted() {},
    computed: {
        testFunc() {
            this.$store.getters["testLog"];
        },
    },
};
</script>
<style scoped>
.home {
    font-family: "Palette Mosaic", cursive;
    font-family: "Sawarabi Gothic", sans-serif;
}
@media (min-width: 1000px) {
    .three-point {
        width: 70%;
    }
    .v-card {
        width: 90%;
    }
}
</style>
