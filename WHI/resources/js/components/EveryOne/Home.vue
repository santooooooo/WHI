<template>
    <v-container>
        <div class="justify-center">
            <transition name="fade">
                <div class="home" v-if="show">
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
                                >テキストの入力だけで自己PRページが手軽に作成可能</v-card-title
                            >
                        </v-card>

                        <v-card
                            class="d-flex justify-space-around ml-auto mr-auto pa-5 mb-5"
                        >
                            <div style="display: grid">
                                <v-icon class="black--text pr-0" size="3rem"
                                    >mdi-application-edit-outline</v-icon
                                >
                                <v-icon class="black--text px-0.5" size="2.5rem"
                                    >mdi-transit-connection-variant</v-icon
                                >
                                <div class="d-flex">
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
                                >ブログやURLを通した外部コンテンツと連携可能</v-card-title
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
                        <v-btn
                            color="orange"
                            class="text-decoration-none white--text"
                            @click="example"
                            >利用例を見る</v-btn
                        >
                        <v-btn color="green"
                            ><router-link
                                to="signup"
                                class="text-decoration-none white--text"
                                >始める</router-link
                            ></v-btn
                        >
                    </div>
                </div>
            </transition>
        </div>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            show: false,
        };
    },
    mounted() {
        this.show = true;
    },
    methods: {
        example() {
            return this.$router.push("/PRpage/1");
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

.fade-enter-active,
.fade-leave-active {
    will-change: opacity;
    transition: opacity 800ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
