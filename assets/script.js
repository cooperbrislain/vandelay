jQuery(document).ready(() => {
    $('table').on('click', 'td.v_id', (e) => {
        let url = "?page=venue&v_id="+$.trim($(e.target).text());
        window.open(url, '_blank');
    });
});