<div class="row mb-4 mt-4" id="overview">
    <!-- Total Tickets Today -->
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-4 border-primary rounded-4">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="bi bi-ticket-perforated-fill text-primary" style="font-size: 2rem;"></i>
                </div>
                <h6 class="text-muted mb-1">Total Tickets Today</h6>
                <h3 class="fw-bold text-primary">{{ overview.today }}</h3>
            </div>
        </div>
    </div>

    <!-- In Progress -->
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-4 border-warning rounded-4">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                </div>
                <h6 class="text-muted mb-1">In Progress</h6>
                <h3 class="fw-bold text-warning">{{ overview.inProgress }}</h3>
            </div>
        </div>
    </div>

    <!-- New -->
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-4 border-success rounded-4">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="bi bi-plus-circle-fill text-success" style="font-size: 2rem;"></i>
                </div>
                <h6 class="text-muted mb-1">New</h6>
                <h3 class="fw-bold text-success">{{ overview.new }}</h3>
            </div>
        </div>
    </div>
</div>


<script>
    new Vue({
        el: '#overview',
        data: {
            overview: {
                new: 0,
                today: 0,
                inProgress: 0
            }
        },
        methods: {
            fetchData() {
                const params = new URLSearchParams(window.location.search);
                const id = params.get('id');

                fetch(`<?= $api ?>/tickets.php?action=overview&id=${id}`, {
                    method: 'get',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.overview = data;
                })
            },
        },
        mounted() {
            this.fetchData();
        }
    })
</script>