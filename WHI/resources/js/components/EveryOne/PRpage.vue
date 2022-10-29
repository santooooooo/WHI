<template>
    <v-container class="white--text pa-5">
        <div class="profile-top ml-auto mr-auto">
            <div class="mb-5 icon-size">
                <v-img :src="iconPath" class="rounded-xl mb-4"> </v-img>
            </div>
            <div class="icon-margin">
                <h1 class="white--text">{{ name }}</h1>
                <div>
                    <v-btn :href="`mailto: ${email}`" class="black"
                        ><v-icon color="red" v-if="email.length > 0"
                            >mdi-email</v-icon
                        ></v-btn
                    >
                    <v-btn
                        :href="`https://twitter.com/${twitter}`"
                        class="black"
                    >
                        <v-icon color="blue" v-if="twitter.length > 0"
                            >mdi-twitter</v-icon
                        >
                    </v-btn>
                </div>
                <p class="white--text" style="white-space: pre-line">
                    {{ career }}
                </p>
            </div>
        </div>
        <h1>{{ title }}</h1>
        <p style="white-space: pre-line">{{ text }}</p>

        <v-expansion-panels>
            <v-expansion-panel v-for="section in sections" :key="section.id">
                <v-expansion-panel-header
                    class="white black--text"
                    style="font-size: 1.4rem"
                    @click="getContentsInSection(section.id)"
                >
                    {{ section.title }}
                </v-expansion-panel-header>
                <v-expansion-panel-content class="black white--text">
                    <v-list-item
                        v-for="content in showContnts"
                        :key="content.id"
                        class="mx-auto mt-2 mb-2 pt-3 white--text row"
                        style="display: grid"
                    >
                        <v-list-item-title
                            v-if="content.type === 'text'"
                            max-width="100%"
                            style="white-space: pre-line"
                        >
                            <div>
                                {{ content.substance }}
                            </div>
                        </v-list-item-title>
                        <v-card
                            :href="content.substance"
                            v-if="content.type === 'url'"
                            class="black--text ogp-pozition"
                        >
                            <v-img
                                class="ogp-img"
                                :src="ogpImage(content.id)"
                            ></v-img>
                            <div>
                                <v-card-title
                                    ><v-img
                                        :src="ogpFavicon(content.id)"
                                        class="mr-2 ogp-favicon-size"
                                    ></v-img
                                    >{{ ogpTitle(content.id) }}
                                </v-card-title>
                                <v-card-text
                                    >{{ ogpDescription(content.id) }}
                                </v-card-text>
                                <v-card-text
                                    >{{ content.substance }}
                                </v-card-text>
                            </div>
                        </v-card>
                        <v-card
                            :href="content.substance"
                            v-if="content.type === 'blog'"
                            class="black--text ogp-pozition"
                        >
                            <v-img
                                class="ogp-img"
                                src="https://whi.s3.amazonaws.com/asset/BlogImage.png"
                            ></v-img>
                            <div>
                                <v-card-title
                                    >{{ blogTitle(content.id) }}
                                </v-card-title>
                                <v-card-text
                                    >{{ content.substance }}
                                </v-card-text>
                            </div>
                        </v-card>
                    </v-list-item>
                    <v-pagination
                        v-model="page"
                        :length="pageLength"
                    ></v-pagination>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            // ユーザー情報
            name: null,
            // プロフィール
            iconPath: null,
            career: null,
            title: null,
            text: null,
            email: "",
            twitter: "",
            // セクション
            sections: [],
            // コンテンツ
            contents: [],
            // コンテンツのすべてのOGP
            ogps: [],
            // 全てのブログのデータ
            blogs: [],
            showOgp: { id: 0, title: "", description: "", image: "", url: "" },
            // 表示数の制御
            page: 1,
            pageLength: 0,
            // 表示するコンテンツ
            contentsInSection: [],
            // 特定のセクション内のコンテンツの表示の制御
            isClose: null,
        };
    },
    methods: {
        // ブログに表示するユーザー情報の取得
        getUserInfo(id) {
            const vm = this;
            axios
                .get("/user/" + id)
                .then(function (response) {
                    if (response.data !== null) {
                        const user = response.data;
                        vm.name = user["name"];
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在ユーザー情報の取得が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // ユーザーのプロフィールの取得
        async getProfile(id) {
            const profile = await axios
                .get("/user/" + id + "/profile")
                .then(function (response) {
                    return response.data;
                })
                .catch(function (error) {
                    alert(
                        "サーバー側の問題により、プロフィールの取得が行えません。問題が解決するまでお待ちください。"
                    );
                    return;
                });

            // 表示するアイコンのURLの取得、なければデフォルト画像のURLを挿入
            this.iconPath =
                profile.icon !== null
                    ? profile.icon
                    : "https://whi.s3.amazonaws.com/asset/FogMan.png";
            this.career = profile.career ?? "";
            this.title = profile.title ?? "";
            this.text = profile.text ?? "";
            this.email = profile.mail ?? "";
            this.twitter = profile.twitter ?? "";
        },
        // 全ての項目の取得
        async getSections(id) {
            const vm = this;
            const sections = await axios
                .get("/user/" + id + "/sections")
                .then(function (response) {
                    if (response.data !== null) {
                        return response.data;
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在項目の取得が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });

            // 項目をメニューへ表示させる
            if (sections !== null) {
                for (const section of sections) {
                    const data = {
                        id: section["id"],
                        title: section["name"],
                    };
                    this.sections.push(data);
                }
            }
        },
        // Sections.vueへ渡す用のコンテンツデータを取得
        getContents(id) {
            const vm = this;
            axios
                .get("/user/" + id + "/contents")
                .then(function (response) {
                    if (response.data !== null) {
                        for (const content of response.data) {
                            const data = {
                                id: content["id"],
                                sectionId: content["section_id"],
                                type: content["type"],
                                substance: content["substance"],
                            };
                            vm.contents.push(data);
                        }
                    }
                    return vm.contents;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在コンテンツの取得が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // URLのOGPの取得
        getOgp(id, url) {
            //　axios.post実行後にthisインスタンスは使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            this.ogps = [];
            const data = {
                url: url,
            };
            axios
                .post("/ogp/", data)
                .then(function (response) {
                    if (response.data !== "Error") {
                        const ogpInfo = response.data;
                        const newOgp = {
                            id: id,
                            title: ogpInfo["title"],
                            description: ogpInfo["description"],
                            image: ogpInfo["image"],
                            favicon: ogpInfo["favicon"],
                        };
                        vm.ogps.push(newOgp);
                    }
                    return;
                })
                .catch(function (error) {
                    const newOgp = {
                        id: id,
                        title: "No data",
                        description: "No description",
                        image: null,
                        favicon: null,
                    };
                    vm.ogps.push(newOgp);
                });
        },
        // 全てのURLのOGPから特定のIDのOGPのオブジェクトを取得
        getOgpObj(id) {
            let result = [];
            this.ogps.forEach((ogp) => {
                if (ogp.id === id) {
                    result = ogp;
                }
            });
            return result;
        },
        // セクションごとのURLの取得するOGPの切り替え
        getContentsInSection(sectionId) {
            if (this.isClose === sectionId) {
                this.contentsInSection = [];
                this.pageLength = 0;
                this.page = 1;
                this.isClose = null;
                return;
            }

            this.page = 1;
            this.contentsInSection = [];
            this.contents.forEach((content) => {
                if (content.sectionId === sectionId) {
                    this.contentsInSection.push(content);
                    if (content.type === "url") {
                        this.getOgp(content.id, content.substance);
                    }
                    if (content.type === "blog") {
                        this.getBlog(content.id, content.substance);
                    }
                }
            });
            this.pageLength = Math.ceil(this.contentsInSection.length / 3);
            this.isClose = sectionId;
        },
        // ブログ情報の取得
        getBlog(contentId, url) {
            this.blogs = [];
            const vm = this;
            // BlogのURLからBlogのIDを取得
            const urlArray = url.split("/");
            const blogId = urlArray[5];
            axios
                .get("/blog/" + blogId)
                .then(function (response) {
                    if (response.data !== null) {
                        const blog = response.data;
                        const newBlog = {
                            id: contentId,
                            title: blog["title"],
                        };
                        vm.blogs.push(newBlog);
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在ブログの取得が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // 全てのブログから特定のIDのブログのオブジェクトを取得
        getBlogObj(id) {
            let result = null;
            this.blogs.forEach((blog) => {
                if (blog.id === id) {
                    result = blog;
                }
            });
            return result;
        },
    },
    computed: {
        // コンテンツの表示
        showContnts() {
            const contents = this.contentsInSection.slice(
                3 * (this.page - 1),
                3 * this.page
            );
            return contents;
        },
        // OGPのタイトル表示
        ogpTitle() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                return ogp.title;
            };
        },
        // OGPのdescription表示
        ogpDescription() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                return ogp.description;
            };
        },
        // OGPの画像表示
        ogpImage() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                return ogp.image;
            };
        },
        // ogpのfavicon表示
        ogpFavicon() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                if (ogp === null) {
                    return null;
                }
                return ogp.favicon;
            };
        },
        // ブログのタイトル表示
        blogTitle() {
            return function (id) {
                const blog = this.getBlogObj(id);
                if (blog === null) {
                    return null;
                }
                return blog.title;
            };
        },
    },
    mounted() {
        // 表示するユーザーのIDの取得
        const userId = this.$route.params.id;
        // ユーザー情報の取得
        this.getUserInfo(userId);
        // プロフィールの取得
        this.getProfile(userId);
        // セクションの取得
        this.getSections(userId);
        // コンテンツの取得
        this.getContents(userId);
    },
};
</script>

<style scoped>
/* ogpの表示が大きくなってもボタンの位置を画面外に出さないようにする */
* {
    overflow-wrap: break-word;
    word-wrap: break-word;
}
.row {
    display: flex;
    flex-direction: row;
}
.row > * {
    min-width: 0;
}
/* 画面幅ごとにOGPの表示を変更 */
@media (min-width: 700px) {
    .profile-top {
        display: flex;
    }
    .icon-size {
        width: 20%;
    }
    .ogp-pozition {
        display: flex;
    }
    .ogp-img {
        max-width: 30%;
        max-height: 17rem;
    }
    .icon-margin {
        margin: 0 0 0 3%;
    }
    .ogp-favicon-size {
        max-width: 2rem;
    }
}
@media (max-width: 699px) {
    .icon-size {
        width: 90%;
    }
    .ogp-img {
        max-width: 100%;
    }
    .icon-margin {
        margin: 0 auto;
    }
    .ogp-favicon-size {
        min-width: 10%;
        max-width: 12%;
    }
}
</style>
