<template>
    <div class="stats-container">
        <div class="stats-overlay">
            <div class="stats-overlay-top">Gráfico de Movimentação</div>
            <div class="stats-overlay-navigation">
                <div class="stats-overlay-navigation-left" @click="prevDay()"></div>
                <div class="stats-overlay-navigation-center">{{this.textWeekDay}}</div>
                <div class="stats-overlay-navigation-right" @click="nextDay()"></div>
            </div>
        </div>
        <div class="stats-placeholder"></div>
        <div class="stats-graph">
                <graph-component 
                    :height="150"
                    :labels="statsGraphLabels"
                    :statsData="statsGraphData"                    
                />
        </div>
    </div>
</template>

<script>
    import GraphComponent from "./GraphComponent.vue";

    export default {
        props: [
            'statsLabels',
            'statsData',
            'lastResults',
            'day'
        ],
        components: {
            'graph-component': GraphComponent
        },
        data() {
            return {
                textWeekDay: '',
                dataDay: '',
                weekDays: {
                    1: "Segunda-feira",
                    2: "Terça-feira",
                    3: "Quarta-feira",
                    4: "Quinta-feira",
                    5: "Sexta-feira",
                    6: "Sábado",
                    7: "Domingo"
                },
                statsGraphLabels: this.statsLabels,
                statsGraphData: this.statsData,
            }
        },
        methods: {

            prevDay() {
                if (this.dataDay <= 1) {
                    this.dataDay = 7
                } else {
                    this.dataDay -= 1
                }
                this.updateGraph()
            },
            nextDay() {
                if (this.dataDay >= 7) {
                    this.dataDay = 1
                } else {
                    this.dataDay += 1
                }
                this.updateGraph()
            },
            updateGraph() {
                this.textWeekDay = this.weekDays[this.dataDay]

                const popularity = this.lastResults.days[this.dataDay].values;
                this.statsGraphData = []
                this.statsGraphLabels = []
                for(let i=0;i<popularity.length;i++) {
                    if (popularity[i] != 0) {
                        this.statsGraphLabels.push(i);
                        this.statsGraphData.push(popularity[i]);
                    }
                }                
            }
        },        
        mounted() {            
            this.weekDays
            this.dataDay = this.day
            this.textWeekDay = this.weekDays[this.dataDay]
        },

    }
</script>