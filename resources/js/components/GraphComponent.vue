<script>
    import { Bar } from 'vue-chartjs'

    export default {
        props: ['labels', 'statsData'],
        extends: Bar,
        watch: {
            statsData() {
                this.$data._chart.destroy()
                this.generateChart()
            }
        },
        methods: {
            generateChart() {
                this.renderChart(
                    {
                        labels: this.labels,
                        datasets: [
                            {
                                label: '',
                                backgroundColor: this.gradient,
                                data: this.statsData
                            }
                        ]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: true,
                            labels: {
                                fontColor: 'rgba(39, 153, 251, 1)'
                            }
                        }
                    }
                )                
            }
        },
        mounted () {
            this.gradient = this.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450)

            this.gradient.addColorStop(0, 'rgba(186, 223, 252, 1)')
            this.gradient.addColorStop(0.5, 'rgba(119, 191, 252, 1)');
            this.gradient.addColorStop(1, 'rgba(39, 153, 251, 1)');

            // Overwriting base render method with actual data.

            this.generateChart()

        }
    }
</script>
