<script>
    // function getNotification() {
    //     axios.get('/api/notifications.php?action=getUnread', {
    //         headers: {
    //             "X-API-Key": "<?= $api_key ?>"
    //         }
    //     }).then(res => {
    //         if (res.data.length > 0) {
    //             res.data.forEach((notif) => {
    //                 let ticketCounts = 0;

    //                 if (notif.model === 'ticket') {
    //                     ticketCounts++;
    //                     toastr.info(`${ticketCounts} New ticket assigned.`);
    //                 }
    //             })
    //         }
    //     })
    // }
    // getNotification();
</script>