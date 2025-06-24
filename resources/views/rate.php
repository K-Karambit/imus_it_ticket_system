<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="plugins/dataTables/buttons.bootstrap4.min.css">

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/vuejs/vue.min.js"></script>


</head>

<body>




    <style>
        .btn {
            border-radius: 0px;
        }

        .rate-question {
            font-size: 20px;
        }

        .rate-items {
            font-size: 50px;
        }

        input {
            border-radius: 0px;
        }
    </style>









    <div class="container mt-2">

        <fieldset class="mb-4">
            <h2 class="text-center">Client Satisfaction Measurement</h2>
            <p class="text-center text-muted">Upang mas mapabuti at lalong mapahusay ang aming serbisyo publiko, makakatulong ang inyong kasagutan ukol sa inyong naging karanasan sa kakatapos lamang na serbisyo.</p>
        </fieldset>

        <!-- <div class="mb-3">
                <label for="date" class="form-label fw-bold">DATE</label>
                <input type="date" name="date" id="date" class="form-control">
            </div> -->

        <div class="mb-3">
            <label for="name_of_client" class="form-label fw-bold">NAME OF CLIENT</label>
            <input id="name_of_client" type="text" v-model="nameOfClient" name="name_of_client" class="form-control">
        </div>


        <!-- <div class="mb-3">
            <label for="name_of_client" class="form-label fw-bold">DEPARTMENT</label>
            <input list="department-list" class="form-control">
            <datalist id="department-list">
                <option v-for="(department, index) in departments" :value="department.name">{{department.name}}</option>
            </datalist>
        </div> -->

        <!-- <div class="mb-3">
                <label for="office" class="form-label fw-bold">OFFICE/DEPARTMENT/AGENCY OF CLIENT</label>
                <input id="1216536528" type="text" name="office" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">ASSIGNED IT PERSONNEL</label>
                <div class="form-check">
                    <input type="radio" name="assigned_it" id="roi" value="ROI VINCENT CIA" class="form-check-input">
                    <label class="form-check-label" for="roi">ROI VINCENT CIA</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="type_of_service" class="form-label fw-bold">TYPE OF SERVICE</label>
                <select id="type_of_service" name="type_of_service" class="form-select">
                    <option value="IT EQUIPMENT REPAIR &amp; MAINTENANCE">IT EQUIPMENT REPAIR & MAINTENANCE</option>
                    <option value="COMPUTER &amp; NETWORK SUPPORT">COMPUTER & NETWORK SUPPORT</option>
                    <option value="SOFTWARE/SYSTEM SUPPORT &amp; MAINTENANCE">SOFTWARE/SYSTEM SUPPORT & MAINTENANCE</option>
                    <option value="INSTALLATION OF VARIOUS SOFTWARE">INSTALLATION OF VARIOUS SOFTWARE</option>
                    <option value="NETWORK CABLING">NETWORK CABLING</option>
                    <option value="TECH SUPPORT FOR VIRTUAL PROJECTS AND PROGRAMS">TECH SUPPORT FOR VIRTUAL PROJECTS AND PROGRAMS</option>
                    <option value="CCTV FOOTAGE">CCTV FOOTAGE</option>
                </select>
            </div> -->

        <!-- <div class="mb-3">
                <label for="1598820186" class="form-label fw-bold">DESCRIPTION</label>
                <input id="1598820186" type="text" name="entry.1598820186" class="form-control" required>
            </div> -->

        <fieldset class="mb-4">
            <legend class="fw-bold">OVERALL SERVICE RATING</legend>
            <p class="text-muted">Please rate with 5 being the highest and 1 being the lowest.</p>

            <p class="fw-bold">{{currentField + 1}}/{{items.length}}</p>
            <button :disabled="currentField==0" class="btn btn-primary" type="button" @click="backField">Back</button>

            <div :hidden="currentField != index" class="mb-2 mt-5" v-for="(item, index) in items">
                <label class="fw-bold mb-3 rate-question">{{item}}:</label>
                <div class="btn-group d-flex" role="group">
                    <input @click="changeCurrentField(index)" type="radio" v-model="rates[index]" class="btn-check" :name="`rate-[${index}][]`" :id="`rate-${index}-5`" value="5">
                    <label class="btn btn-outline-primary fs-5" :for="`rate-${index}-5`">5</label>

                    <input @click="changeCurrentField(index)" type="radio" v-model="rates[index]" class="btn-check" :name="`rate-[${index}][]`" :id="`rate-${index}-4`" value="4">
                    <label class="btn btn-outline-primary fs-5" :for="`rate-${index}-4`">4</label>

                    <input @click="changeCurrentField(index)" type="radio" v-model="rates[index]" class="btn-check" :name="`rate-[${index}][]`" :id="`rate-${index}-3`" value="3">
                    <label class="btn btn-outline-primary fs-5" :for="`rate-${index}-3`">3</label>

                    <input @click="changeCurrentField(index)" type="radio" v-model="rates[index]" class="btn-check" :name="`rate-[${index}][]`" :id="`rate-${index}-2`" value="2">
                    <label class="btn btn-outline-primary fs-5" :for="`rate-${index}-2`">2</label>

                    <input @click="changeCurrentField(index)" type="radio" v-model="rates[index]" class="btn-check" :name="`rate-[${index}][]`" :id="`rate-${index}-1`" value="1">
                    <label class="btn btn-outline-primary fs-5" :for="`rate-${index}-1`">1</label>
                </div>
            </div>

        </fieldset>

        <div class="mb-3">
            <label for="705601032" class="form-label fw-bold">FEEDBACK &amp; SUGGESTION</label>
            <textarea v-model="feedback" id="705601032" name="entry.705601032" class="form-control" rows="3"></textarea>
        </div>

        <button type="button" @click.prevent="submitClientRating" class="btn btn-primary w-100">Submit</button>


        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    </div>


    <script>
        new Vue({
            el: '.container',

            data: {
                currentField: 0,
                nameOfClient: null,
                feedback: null,
                departments: [],
                rates: [],
                data: [],
                items: [
                    "Nasiyahan ako sa serbisyo na aking natanggap.",
                    "Ang oras ng proseso ay mabilis.",
                    "Ang opisina ay sumusunod sa mga kinakailangang dokumento at mga hakbang batay sa Citizen's Charter",
                    "Mabilis at madali akong makanap ng impormasyon tungkol sa aking transaksyon mula sa opisina, website, at social media.",
                    "Pakiramdam ko ay patas ang opisina sa lahat o walang 'palakasan' sa aking transaksyon.",
                    "Magalang, magiliw at labis na nakatulong ang empleyadong nakausap.",
                    "Nakuha ko ang kinakailangan sa tanggapan, at kung di man nakuha ay naipaliwanag ito nang maayos."
                ],
            },
            methods: {
                changeCurrentField(index) {
                    setTimeout(() => {
                        const question = this.items[index];
                        const rate = this.rates[index];

                        const existingIndex = this.data.findIndex(item => item.question === question);

                        if (existingIndex !== -1) {
                            if (this.data[existingIndex].rate !== rate) {
                                this.data[existingIndex].rate = rate;
                            }
                        } else {
                            this.data.push({
                                question,
                                rate
                            });
                        }

                        //console.log(JSON.stringify(this.data));
                    }, 1000);

                    if (this.currentField < this.items.length - 1) {
                        setTimeout(() => {
                            this.currentField++;
                        }, 1000);
                    }
                },
                backField() {
                    if (this.currentField == 0) {
                        return;
                    }
                    this.currentField--;
                },
                submitClientRating() {
                    const params = new URLSearchParams(window.location.search);
                    const userId = params.get('user_id');

                    const jsonData = {
                        user_id: userId,
                        name_of_client: this.nameOfClient,
                        feedback: this.feedback,
                        data: JSON.stringify(this.data),
                    };

                    if (!this.nameOfClient) {
                        alert('Please input your name');
                        return;
                    }

                    if (this.items.length != this.data.length) {
                        alert('Please complete your ratings');
                        return;
                    }

                    fetch('<?= $api ?>/rate.php?action=submit', {
                        method: 'post',
                        body: JSON.stringify(jsonData),
                        headers: {
                            "Content-Type": "application/json",
                            "X-API-Key": "<?= $api_key ?>"
                        }
                    }).then(res => res.json()).then(data => {
                        if (data.status === 'success') {
                            toastr.success(data.message);
                            return;
                        }

                        toastr.error(data.message);
                    })

                    alert("Thanks for your cooperation—we appreciate it!");
                    console.log(JSON.stringify(jsonData));
                },
                // fetchDepartments() {
                //     fetch('<?= $api ?>/department?action=all', {
                //         method: 'GET',
                //         headers: {
                //             "X-API-Key": "<?= $api_key ?>"
                //         }
                //     }).then(res => res.json()).then(data => {
                //         this.departments = data;
                //     })
                // },
            },
            mounted() {
                // this.fetchDepartments();
            }
        })
    </script>
</body>

</html>