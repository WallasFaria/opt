<template>
    <div class="stats-container">
        <div class="stats-overlay">
            <div class="stats-overlay-top">Gráfico de Movimentação</div>
            <div class="stats-overlay-navigation">
                <div class="stats-overlay-navigation-left"></div>
                <div class="stats-overlay-navigation-center">Domingo</div>
                <div class="stats-overlay-navigation-right"></div>
            </div>
        </div>
        <div class="stats-placeholder"></div>
        <div class="stats-graph">
                <graph-component :height="150" />
        </div>
    </div>
</template>

<script>
    import GraphComponent from "./GraphComponent.vue";

    export default {
        components: {
            'graph-component': GraphComponent
        },

        mounted() {
            
        },

    }
</script>