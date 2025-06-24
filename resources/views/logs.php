<div class="content-wrapper" id="logs">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>Activities</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a class="route-link" href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Activities</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <table id="dataTable" class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th class="d-none">#</th>

                                        <th>Description</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(log, index) in logs" :key="index">
                                        <td class="d-none">{{index + 1}}</td>

                                        <td>{{log.log_details}}</td>
                                        <td>{{log.date_created}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>



                </div>

            </div>

        </div>

    </section>










</div>


<script>
    new Vue({
        el: '#logs',
        data: {
            logs: [],
        },
        methods: {
            fetchActivity() {
                let url = "<?= $api ?>/logs.php?action=all";

                if (<?= $session_user->role ?> != 1) {
                    url = "<?= $api ?>/logs.php?action=all&userid=<?= $session_user->user_id ?>";
                }

                fetch(url, {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    $('#dataTable').DataTable().destroy();
                    this.logs = data;
                    Vue.nextTick(() => {
                        $('#dataTable').DataTable({
                            "paging": true,
                            "lengthChange": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "autoWidth": false,
                            "responsive": true,
                        });
                    });
                })
            },
        },
        mounted() {
            this.fetchActivity();
        }
    })
</script>