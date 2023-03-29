$(document).ready(function (event) {
  // Funciones
  function fetchPurchases() {
    $.ajax({
      url: './php/purchases/get-all.php',
      method: 'GET',
      success: function (data) {
        const purchases = JSON.parse(data);

        let htmlTBody = '';
        if (purchases.length > 0) {
          purchases.map((purchase) => {
            htmlTBody += `
              <tr>
                <td>${purchase.id}</td>
                <td>${purchase.client}</td>
                <td>${purchase.total}</td>
                <td data-state="${purchase.state}">${purchase.state == 0 ? 'Cerrada' : 'Abierta'}</td>
                <td>${purchase.date}</td>
              </tr>
            `;
          });
          $('#tbody').html(htmlTBody);

          $('#tbody tr').click(function (event) {
            const state = $(this).find('td[data-state]').data('state');
            const id = $(this).find('td').eq(0).html();

            if (state !== 0) {
              const client = $(this).find('td').eq(1).html();
              const state = $(this).find('td').eq(3).data('state');

              $.ajax({
                url: './php/invoices/get-all-by-purchase.php',
                method: 'POST',
                data: {
                  idPurchase: id,
                },
                success: function (data) {
                  const invoices = JSON.parse(data);

                  const numberProducts = invoices.length;

                  $('#id').val(id);
                  $('#client').val(client);
                  $('#productsNumber').val(numberProducts);
                  $('#state').prop('checked', state == 1);

                  buildInputsProducts(invoices);

                  $('#actionModal').modal('show');
                },
              });
            } else {
              window.open(`./php/invoices/pdf/allInvoicesByPurchases.php?id=${id}`, '_blank');
            }
          });
        } else {
          htmlTBody = `
            <tr>
              <td colspan="5">No hay registros</td>
            </tr>
          `;
          $('#tbody').html(htmlTBody);
        }
      },
    });
  }
  function buildInputsProducts(productsToLoad) {
    const productsContainer = $('#productsContainer');
    let htmlProductsContainer = '';

    $.ajax({
      url: './php/products/get-all.php',
      method: 'GET',
      success: async function (productsData) {
        const listProducts = JSON.parse(productsData);

        let htmlOptionsProducts = '<option>Productos</option>';
        listProducts.map(function (product) {
          // Comprobar si el producto tiene stock
          if (product.stock > 0) {
            htmlOptionsProducts += `
            <option value="${product.id}" data-price="${product.price}" data-stock="${product.stock}">${product.name}</option>
          `;
          }
        });

        for (let i = 0; i < $('#productsNumber').val(); i++) {
          htmlProductsContainer += `
            <div class="row g-3 mb-3">
              <div class="col-6">
                <select class="form-select" name="productSelected">${htmlOptionsProducts}</select>
              </div>
              <div class="col-6">
                <input class="form-control" type="number" name="productQuantity" min="1" step="1"/>
              </div>
            </div>
          `;
        }
        productsContainer.html(htmlProductsContainer);

        function calculateTotal() {
          let total = 0;
          $("select[name='productSelected']").each(function (index) {
            const price = $(this).find(':selected').data('price');
            const quantity = $("input[name='productQuantity']")[index].value;
            total += price * quantity;
          });
          $('#total').html(total || 0);
        }

        $("input[name='productQuantity']").change(function () {
          calculateTotal();
        });
        $("input[name='productQuantity']").keyup(function () {
          calculateTotal();
        });
        $("select[name='productSelected']").change(function () {
          calculateTotal();

          // Actualizar el maximo posible del producto seleccionado
          const stock = $(this).find(':selected').data('stock');
          $(this).parent().next().find('input').attr('max', stock);

          // Validar que no se repitan los productos
          const productsSelected = [];
          $("select[name='productSelected']").each(function (index) {
            const id = $(this).val();
            productsSelected.push(id);
          });
          const productsSelectedUnique = [...new Set(productsSelected)];
          if (productsSelected.length !== productsSelectedUnique.length) {
            alert('No se pueden repetir los productos.');
            $(this).val('Productos');
          }
        });

        if (productsToLoad) {
          // Cargar los productos de la compra
          productsToLoad.map(function (invoice, index) {
            const productSelected = $("select[name='productSelected']")[index];
            const productQuantity = $("input[name='productQuantity']")[index];

            $(productSelected).val(invoice.idProducts);
            $(productQuantity).val(invoice.quantity);
          });

          calculateTotal();

          // Actualizar el maximo posible de los productos
          $("select[name='productSelected']").each(function (index) {
            const stock = $(this).find(':selected').data('stock');
            $(this).parent().next().find('input').attr('max', stock);
          });
        }
      },
    });
  }
  function createPurchase() {
    const client = $('#client').val();
    const state = $('#state').is(':checked') ? 1 : 0;
    const total = $('#total').html();

    let isValid = client !== '' && state !== '' && total !== '';

    const products = [];
    $("select[name='productSelected']").each(function (index) {
      const id = $(this).val();
      const quantity = $("input[name='productQuantity']")[index].value;

      isValid = isValid && id !== '' && id !== 'Productos' && quantity !== '' && quantity > 0;

      products.push({
        id,
        quantity,
      });
    });

    isValid = isValid && products.length > 0;

    if (isValid) {
      $.ajax({
        url: './php/purchases/create.php',
        method: 'POST',
        data: {
          client,
          state,
          total,
        },
        success: function (data) {
          const idPurchases = JSON.parse(data);

          if (idPurchases !== undefined) {
            $.ajax({
              url: './php/invoices/create.php',
              method: 'POST',
              data: {
                idPurchases: idPurchases,
                products,
              },
              success: function (data) {
                console.log(data);
                fetchPurchases();
                $('#formModal').modal('hide');
              },
            });
          } else {
            alert(
              'Error al crear la compra, se necesita el id de la compra para registrar los productos de la compra.'
            );
          }
        },
      });
    } else {
      alert('Todos los campos son obligatorios.');
    }
  }
  function updatePurchase() {
    const id = $('#id').val();
    const client = $('#client').val();
    const state = $('#state').is(':checked') ? 1 : 0;
    const total = $('#total').html();

    let isValid = id !== '' && client !== '' && state !== '' && total !== '';

    const products = [];
    $("select[name='productSelected']").each(function (index) {
      const id = $(this).val();
      const quantity = $("input[name='productQuantity']")[index].value;

      isValid = isValid && id !== '' && id !== 'Productos' && quantity !== '' && quantity > 0;

      products.push({
        id,
        quantity,
      });
    });

    isValid = isValid && products.length > 0;

    if (isValid) {
      $.ajax({
        url: './php/invoices/delete-by-purchase.php',
        method: 'POST',
        data: {
          idPurchase: id,
        },
        success: function (data) {
          console.log(data);
          $.ajax({
            url: './php/invoices/create.php',
            method: 'POST',
            data: {
              idPurchases: id,
              products,
            },
            success: function (data) {
              console.log(data);
              $.ajax({
                url: './php/purchases/update.php',
                method: 'POST',
                data: {
                  id,
                  client,
                  state,
                  total,
                },
                success: function (data) {
                  console.log(data);
                  fetchPurchases();
                  $('#formModal').modal('hide');
                },
              });
            },
          });
        },
      });
    } else {
      alert('El id de la compra es obligatorio.');
    }
  }
  function deletePurchase() {
    const id = $('#id').val();

    if (id !== '') {
      $.ajax({
        url: './php/invoices/delete-by-purchase.php',
        method: 'POST',
        data: {
          idPurchase: id,
        },
        success: function (data) {
          console.log(data);
          $.ajax({
            url: './php/purchases/delete.php',
            method: 'POST',
            data: {
              id,
            },
            success: function (data) {
              console.log(data);
              fetchPurchases();
              $('#actionModal').modal('hide');
            },
          });
        },
      });
    } else {
      alert('El id de la compra es obligatorio.');
    }
  }
  function graphicPurchases() {
    $.ajax({
      url: './php/invoices/get-all.php',
      method: 'GET',
      success: function (data) {
        const invoices = JSON.parse(data);

        $.ajax({
          url: './php/products/get-all.php',
          method: 'GET',
          success: function (data) {
            const products = JSON.parse(data);

            // Obtener el nombre y la cantidad comprada de cada producto
            const productsPurchased = [];
            products.forEach((product) => {
              let quantity = 0;
              invoices.forEach((invoice) => {
                if (invoice.idProducts === product.id) {
                  quantity += Number(invoice.quantity);
                }
              });
              productsPurchased.push({
                name: product.name,
                quantity,
              });
            });

            // Grafico de barras con la cantidad de productos comprados
            const ctx = document.getElementById('chart');
            if (window.chart.destroy) {
              window.chart.destroy();
            }
            window.chart = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: productsPurchased.map((product) => product.name),
                datasets: [
                  {
                    label: 'Cantidad de productos comprados',
                    data: productsPurchased.map((product) => product.quantity),
                    backgroundColor: productsPurchased.map(() => 'rgba(54, 162, 235, 0.2)'),
                    borderColor: productsPurchased.map(() => 'rgba(54, 162, 235, 1)'),
                    borderWidth: 1,
                  },
                ],
                options: {
                  scales: {
                    y: {
                      beginAtZero: true,
                    },
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

  // Implementacion
  fetchPurchases();
  $('#showModalButton').click(function (event) {
    $('#form')[0].reset();
    $('#productsContainer').html('');
    $('#id').val('');
  });
  $('#productsNumber').change(function (event) {
    buildInputsProducts();
  });
  $('#productsNumber').keyup(function (event) {
    buildInputsProducts();
  });
  $('#deleteButton').click(function (event) {
    deletePurchase();
  });
  $('#updateButton').click(function (event) {
    $('#actionModal').modal('hide');
    $('#formModal').modal('show');
  });
  $('#form').submit(function (event) {
    event.preventDefault();
    if ($('#id').val() === '') {
      createPurchase();
    } else {
      updatePurchase();
    }
  });
  $('#downloadPdfAllPurchasesButton').click(function (event) {
    window.open('./php/purchases/pdf/allPurchases.php');
  });
  $('#donwloadPdfOpenPurchasesButton').click(function (event) {
    window.open('./php/purchases/pdf/openPurchases.php');
  });
  $('#donwloadPdfClosePurchasesButton').click(function (event) {
    window.open('./php/purchases/pdf/closePurchases.php');
  });
  $('#graphicModalButton').click(function (event) {
    graphicPurchases();
  });
});
