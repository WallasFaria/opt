<script>
    import { Bar } from 'vue-chartjs'
    
    export default {
        extends: Bar,
        mounted () {
            this.gradient = this.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450)

            this.gradient.addColorStop(0, 'rgba(186, 223, 252, 1)')
            this.gradient.addColorStop(0.5, 'rgba(119, 191, 252, 1)');
            this.gradient.addColorStop(1, 'rgba(39, 153, 251, 1)');

            // Overwriting base render method with actual data.
            this.renderChart({
            labels: ['09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'],
            datasets: [
                {
                    label: '',
                    backgroundColor: this.gradient,
                    data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 10, 2, 5, 4]
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
    }
</script>