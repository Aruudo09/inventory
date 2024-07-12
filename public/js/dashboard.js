//ajax
$.ajax({
  url: '/dashboard/chart',
  type: 'GET',
  dataType: 'json',
  success: function(data) {

    // console.log(data[0]);

    // CHART JS
    new Chart(document.getElementById("line-chart"), {
      type : 'line',
      data : {
        labels : [ 'Januari', 'Febuari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
            'Agustus', 'September', 'Oktober', 'November', 'Desember' ],
        datasets : [
            {
              data : data[0],
              label : "Barang Masuk",
              borderColor : "#3cba9f",
              fill : false
            },
            {
              data : data[1],
              label : "Barang Keluar",
              borderColor : "#e43202",
              fill : false
            } ]
      },
      options : {
        title : {
          display : true,
          text : 'Chart JS Multiple Lines Example'
        }
      }
    });
  }
});