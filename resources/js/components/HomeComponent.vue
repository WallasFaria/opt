<template>
    <div class="main">
        <div class="logo">
            <img src="svg/logo.png" alt="Logo Opt">
        </div>

        <div class="content">
            <transition name='slide-up'>
                <search-component
                    :nameClass="'transition'"
                    @onKeyPress="search"/>
            </transition>
        </div>
    </div>
</template>

<script>
    import SearchComponent from "./SearchComponent.vue";

    export default {
        components: {
            'search-component': SearchComponent
        },

        methods: {
            search(query) {
                this.$router.push({
                    path: '/search',
                    query: { q: query, radius: 2000 }
                })
            }
        }
    }
</script>
