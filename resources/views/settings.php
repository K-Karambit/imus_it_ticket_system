<div class="content-wrapper" id="settings">



    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>Settings</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a class="route-link" href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>




    <section class="content">
        <div class="container-fluid">
            <div class="">
                <form @submit.prevent="updateConfigurations" class="m-4">
                    <div class="form-group">
                        <label for="api">API</label>
                        <input type="text" class="form-control" v-model="api" id="api" placeholder="Enter api">
                    </div>
                    <div class="form-group">
                        <label for="key">API Key</label>
                        <textarea class="form-control" id="key" v-model="key" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </section>





</div>



<script>
    new Vue({
        el: '#settings',
        data: {
            data: [],
            api: '<?= $api ?>',
            key: '<?= $api_key ?>',
        },
        methods: {
            updateConfigurations() {
                if (!confirm('Are you sure you want to update configurations?')) {
                    return;
                }

                fetch(`configurations.php?action=update&KEY=${this.key}&API=${this.api}`).then(res => res.json()).then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        return;
                    }
                    toastr.error(data.message);
                })
            }
        }
    })
</script>