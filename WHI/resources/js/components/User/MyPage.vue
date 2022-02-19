<template>
    <div class="d-flex">
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant.sync="mini"
            permanent
            class="mt-15 ml-5"
        >
            <v-list-item class="px-2">
                <v-list-item-avatar>
                    <v-img
                        src="https://randomuser.me/api/portraits/men/1.jpg"
                    ></v-img>
                </v-list-item-avatar>

                <v-list-item-title>{{
                    $store.state.user.name
                }}</v-list-item-title>

                <v-btn icon @click.stop="mini = !mini">
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
            </v-list-item>

            <v-divider class="black"></v-divider>

            <v-list dense>
                <v-list-item v-for="item in items" :key="item.title" link>
                    <v-list-item-title>
                        <div @click="section = item.sectionName">
                            {{ item.title }}
                        </div>
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <div class="mt-15 ml-5">
            <profile v-if="section === 'Profile'" />
        </div>
    </div>
</template>

<script>
import Profile from "./Profile.vue";

export default {
    components: {
        Profile, // ユーザーのプロフィール表示・更新を行う
    },
    data() {
        return {
            drawer: true,
            mini: true,
            // ユーザーページの左側のナビゲーションに表示するアイテム、titleは表示名、sectionNameは表示するComponentの切り替えに使用
            items: [
                {
                    title: "ユーザー情報の変更",
                    sectionName: "changeUserInfo",
                },
                {
                    title: "プロフィール",
                    sectionName: "Profile",
                },
            ],
            // 表示するComponentの切り替えに使用
            section: 'Profile',
        };
    },
    mounted() {
        // アクセス先がユーザーではない場合は、トップページへ移動させる
        if (
            this.$store.state.user.id !== null &&
            this.$store.state.user.name !== null
        ) {
            return;
        }
        return this.$router.push("/");
    },
};
</script>
