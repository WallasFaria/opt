<template>
    <div>
        <transition name='slide-up'>
            <search-component
                v-if="showSearch"
                :value="query.name"
                :className="'top'" />
        </transition>

        <div class="main">
            <div class="content">
                <div class="result">
                    <card-component v-for="(place, index) in places"
                        :key="index"
                        :place="place" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SearchComponent from "./SearchComponent.vue";
    import CardComponent from "./CardComponent.vue";

    export default {
        components: {
            'search-component': SearchComponent,
            'card-component': CardComponent,
        },

        mounted() {
            this.showSearch = true;
            // axios.get('/api/places/', this.query)
            //     .then(result => {
            //         console.log(result)
            //     })
        },

        data() {
            return {
                places: [],
                sortOption: ['distance', 'popularity'],
                query:  {
                    name: this.$route.query.q,
                    radius: 5000,
                    lat: 0,
                    lon: 0,
                    sortBy: 'popularity'
                },
                showSearch: false
            }
        },

    }
</script>

<style>
    .slide-up-enter-active {
        transition: all .5s;
    }
    .slide-up-enter {
        transform: translateY(200px);
    }
</style>
