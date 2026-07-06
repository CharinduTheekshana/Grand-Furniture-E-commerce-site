(function() {
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(function(el) {
            var end  = new Date(el.dataset.end).getTime();
            var now  = new Date().getTime();
            var diff = end - now;

            if (diff <= 0) {
                var badge = el.closest('.offer-badge');
                if (badge) badge.remove();
                var countdown = el.closest('.offer-countdown, .product-offer-countdown-detail');
                if (countdown) countdown.remove();
                return;
            }

            var days    = Math.floor(diff / (1000 * 60 * 60 * 24));
            var hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((diff % (1000 * 60)) / 1000);

            el.textContent =
                String(days).padStart(2,'0')    + 'd ' +
                String(hours).padStart(2,'0')   + 'h ' +
                String(minutes).padStart(2,'0') + 'm ' +
                String(seconds).padStart(2,'0') + 's';
        });
    }

    updateCountdowns();
    setInterval(updateCountdowns, 1000);
})();