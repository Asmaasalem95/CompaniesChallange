<template >

    <div>
        <div class="container">
            <form action="" class="form-inline" method="post">
                <p v-if="errors.length">
                    <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
                </p>
                <div class="form-group mb-4 mr-4 col-md-12">
                    <label>
                        <select class="form-control mb-2 mr-2 col-md-10" id="company-symbol" name="company_symbol"
                                v-model="selectedSymbol">
                            <option disabled selected value="null">Company Symbol</option>
                            <option :value="symbol" v-for="symbol in symbols"> {{symbol}}</option>
                        </select>
                    </label>
                </div>

                <div class="form-group mb-4 mr-4 col-md-12">
                    <label>
                        <datepicker placeholder="Pick start date" class="form-control mb-2 mr-2 col-md-10" name="start_date"
                                    v-model="startDate"></datepicker>
                    </label>
                </div>
                <div class="form-group mb-4 mr-4 col-md-12">
                    <label>
                        <datepicker  placeholder="Pick start date"  class="form-control mb-2 mr-2 col-md-10" name="end_date"
                                    v-model="endDate"></datepicker>
                    </label>
                </div>
                <div class="form-group mb-4 mr-4 col-md-12">
                    <label>
                        <input class="form-control mb-2 mr-2 col-md-10" placeholder="Enter Email" name="email" type="email"
                               v-model="email">

                    </label>
                </div>

                <button @click="submit($event)" class="form-control btn btn-primary mb-2 col-md-6" type="button">Filter
                </button>
                <button @click="reset()" class="form-control btn btn-secondary mb-2 col-md-6" type="button">Reset
                </button>
            </form>

            <br>
            <div class="table-responsive " v-if="displayTable">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Open</th>
                        <th>Close</th>
                        <th>High</th>
                        <th>Low</th>
                        <th>Volume</th>

                    </tr>
                    </thead>
                    <tbody class="">
                    <tr v-for="company in companies">
                        <td>{{company.date}}</td>
                        <td>{{company.open}}</td>
                        <td>{{company.close}}</td>
                        <td>{{company.high}}</td>
                        <td>{{company.low}}</td>
                        <td>{{company.volume}}</td>

                    </tr>
                    </tbody>
                </table>
            </div>


            <br>
        </div>
        <div v-show="displayChart">
            <canvas height="400" id="chart" width="400"></canvas>
        </div>
    </div>


</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import Chart from 'chart.js'


    export default {
        name: "form-component",
        props: ['symbols'],
        components: {
            Datepicker,
        },
        data() {
            return {
                companies: {},
                selectedSymbol: null,
                displayTable: false,
                displayChart: false,
                email: null,
                startDate: null,
                endDate: null,
                errors: [],
            }
        },
        methods: {
            submit(e) {
                this.displayTable = false;
                this.displayChart = false;

                this.checkForm();
                if (!this.errors.length) {
                    axios
                        .post("/api/companies", {
                            email: this.email,
                            start_date: this.startDate,
                            end_date: this.endDate,
                            symbol: this.selectedSymbol,
                        })
                        .then(response => response.data)
                        .then(response => {
                            this.displayTable = true;
                            this.companies = response.data.tableData;
                            this.buildChart(response.data.chartData);
                        })
                        .catch(e => {
                             this.errors.push(e.response.data.response)
                        })
                }
            },
            checkForm() {
                this.errors = [];

                if (!this.selectedSymbol) {
                    this.errors.push("Company Symbol required.");
                }
                if (!this.email) {
                    this.errors.push('Email required.');
                } else if (!this.validEmail(this.email)) {
                    this.errors.push('Valid email required.');
                }
                if (!this.startDate) {
                    this.errors.push('Start Date required.');
                } else if (!this.validStartDate(this.startDate)) {
                    this.errors.push('Valid start Date required.');
                }
                if (!this.endDate) {
                    this.errors.push('End Date required.');
                } else if (!this.validEndDate(this.endDate)) {
                    this.errors.push('Valid End Date required.');
                }

                if (!this.errors.length) {
                    return true;
                }
            },
            validEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            validStartDate(startDate) {
                var validDate = Date.parse(startDate);

                if (isNaN(validDate)) {
                    return false
                } else return new Date(startDate.toDateString()) < new Date(new Date().toDateString()) && new Date(startDate.toDateString()) < new Date(this.endDate.toDateString());

            },
            validEndDate(endDate) {
                var validDate = Date.parse(endDate);

                if (isNaN(validDate)) {
                    return false
                } else return new Date(endDate.toDateString()) < new Date(new Date().toDateString()) && new Date(endDate.toDateString()) > new Date(this.startDate.toDateString());

            },

            buildChart(data) {

                this.displayChart = true;
                console.log(this.displayChart);
                var chart = document.getElementById("chart");

                Chart.defaults.global.defaultFontFamily = "Lato";
                Chart.defaults.global.defaultFontSize = 18;

                var dataFirst = {
                    label: "Open",
                    data: data.open,
                    lineTension: 0,
                    fill: false,
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                    ],
                    borderWidth: 1
                };

                var dataSecond = {
                    label: "Close",
                    data: data.close,
                    lineTension: 0,
                    fill: false,
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                    ],
                    borderWidth: 1
                };



                var ChartData = {
                    labels: ["50", "100", "150", "200", "250", "300"],
                    datasets: [dataFirst, dataSecond]
                };

                var chartOptions = {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 80,
                            fontColor: 'black'
                        }
                    }
                };

                var lineChart = new Chart(chart, {
                    type: 'line',
                    data: ChartData,
                    options: chartOptions
                });
            },

            reset() {
                this.selectedSymbol = null;
                this.errors = {};
                this.companies = {};
                this.email = null;
                this.startDate = null;
                this.endDate = null;
                this.errors = [];
                this.displayTable = false;
                this.displayChart = false;
            }
        }
    }
</script>

<style scoped>

</style>
