$(document).ready(function () {
  // Funciones
  function fetchProductions() {
    $.ajax({
      url: './php/productions/get-all.php',
      method: 'GET',
      success: function (data) {
        const productions = JSON.parse(data);
        let htmlTBody = '';

        $.ajax({
          url: './php/products/get-all.php',
          method: 'GET',
          success: function (data) {
            const products = JSON.parse(data);

            if (productions.length > 0) {
              productions.map((production) => {
                const productName = products.find((product) => product.id === production.idProducts).name;
                htmlTBody += `
                  <tr>
                    <td>${production.id}</td>
                    <td data-id="${production.idProducts}">${productName}</td>
                    <td>${production.quantity}</td>
                    <td data-state="${production.state}">${production.state == 0 ? 'En producción' : 'Producido'}</td>
                  </tr>
                `;
              });
              $('#tbody').html(htmlTBody);

              $('#tbody tr').click(function (event) {
                let state = $(this).find('td').eq(3).data('state');
                if (state == 0) {
                  let id = $(this).find('td').eq(0).html();
                  let idProducts = $(this).find('td').eq(1).data('id');
                  let quantity = $(this).find('td').eq(2).html();

                  $('#id').val(id);
                  $('#product').val(idProducts);
                  $('#quantity').val(quantity);

                  $('#actionModal').modal('show');
                } else {
                  alert('No se puede modificar un registro que ya fue producido');
                }
              });
            } else {
              htmlTBody = `
                <tr>
                  <td colspan="4">No hay registros</td>
                </tr>
              `;
              $('#tbody').html(htmlTBody);
            }
          },
        });
      },
    });
  }
  function fetchProducts() {
    $.ajax({
      url: './php/products/get-all.php',
      method: 'GET',
      success: function (data) {
        const products = JSON.parse(data);

        let htmlSelect = '<option selected>Seleccione un producto</option>';
        products.map((product) => {
          htmlSelect += `
            <option value="${product.id}">${product.name}</option>
          `;
        });
        $('#products').html(htmlSelect);
      },
    });
  }
  function createProduction() {
    const product = $('#products').val();
    const quantity = $('#quantity').val();

    if (product !== 'Seleccione un producto' && product > 0 && quantity !== '' && quantity > 0) {
      $.ajax({
        url: './php/productions/create.php',
        method: 'POST',
        data: {
          idProducts: product,
          quantity: quantity,
        },
        success: function (data) {
          console.log(data);
          fetchProductions();
          $('#formModal').modal('hide');
        },
      });
    } else {
      alert('Todos los campos son obligatorios');
    }
  }
  function updateProduction() {
    const id = $('#id').val();
    const product = $('#product').val();
    const quantity = $('#quantity').val();

    if (id !== '' && id > 0 && product !== '' && product > 0 && quantity !== '' && quantity > 0) {
      $.ajax({
        url: './php/productions/update-state.php',
        method: 'POST',
        data: {
          id: id,
          idProducts: product,
          quantity: quantity,
        },
        success: function (data) {
          console.log(data);
          fetchProductions();
          $('#actionModal').modal('hide');
        },
      });
    } else {
      alert('Todos los campos son obligatorios');
    }
  }
  function deleteProduction() {
    const id = $('#id').val();

    if (id !== '' && id > 0) {
      $.ajax({
        url: './php/productions/delete.php',
        method: 'POST',
        data: {
          id: id,
        },
        success: function (data) {
          console.log(data);
          fetchProductions();
          $('#actionModal').modal('hide');
        },
      });
    } else {
      alert('Todos los campos son obligatorios');
    }
  }
  function graphicProductions() {
    $.ajax({
      url: './php/productions/get-all.php',
      method: 'GET',
      success: function (data) {
        const productions = JSON.parse(data);

        $.ajax({
          url: './php/products/get-all.php',
          method: 'GET',
          success: function (data) {
            const products = JSON.parse(data);

            // Obtener cuanto se produjo de cada producto
            let productionsByProduct = [];
            products.map((product) => {
              let quantity = 0;
              productions.map((production) => {
                if (production.idProducts == product.id) {
                  quantity += Number(production.quantity);
                }
              });
              productionsByProduct.push({
                id: product.id,
                name: product.name,
                quantity: quantity,
              });
            });

            // Grafico de barra con la cantidad producida de cada producto
            const ctx = document.getElementById('chart');
            if (window.chart.destroy) {
              window.chart.destroy();
            }
            window.chart = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: productionsByProduct.map((production) => production.name),
                datasets: [
                  {
                    label: 'Cantidad de productos fabricados',
                    data: productionsByProduct.map((production) => production.quantity),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                  },
                ],
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true,
                  },
                },
              },
            });

            $('#graphicModal').modal('show');
          },
        });
      },
    });
  }

  // Implementaciones
  fetchProductions();
  fetchProducts();
  $('#showModalButton').click(function (event) {
    $('#id').val('');
    $('#product').val('');
    $('#quantity').val('');
    $('#form')[0].reset();
  });
  $('#form').submit(function (event) {
    event.preventDefault();
    createProduction();
  });
  $('#deleteButton').click(function (event) {
    deleteProduction();
  });
  $('#updateButton').click(function (event) {
    updateProduction();
  });
  $('#downloadPdfAllProductionsButton').click(function (event) {
    window.open('./php/productions/pdf/allProductions.php');
  });
  $('#donwloadPdfInProgressProductionsButton').click(function (event) {
    window.open('./php/productions/pdf/inProgressProductions.php');
  });
  $('#donwloadPdfFinishedProductionsButton').click(function (event) {
    window.open('./php/productions/pdf/finishedProductions.php');
  });
  $('#graphicModalButton').click(function (event) {
    graphicProductions();
  });
});
