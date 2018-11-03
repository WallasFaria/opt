<template>
    <div class="main">
        <div class="logo">
            <img src="svg/logo.svg" alt="Logo Opt">
        </div>

        <div class="content">
            <search-component />
        </div>
    </div>
</template>

<script>
    import SearchComponent from "./SearchComponent.vue";
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        components: {
            'search-component': SearchComponent
        }
    }
</script>
