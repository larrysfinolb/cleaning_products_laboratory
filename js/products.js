$(document).ready(function () {
  // Funciones
  function fetchProducts() {
    $.ajax({
      url: './php/products/get-all.php',
      method: 'GET',
      success: function (data) {
        const products = JSON.parse(data);

        let htmlTBody = '';
        if (products.length > 0) {
          products.map((product) => {
            htmlTBody += `
              <tr>
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>${product.stock}</td>
                <td>${product.price}</td>
              </tr>
            `;
          });
          $('#tbody').html(htmlTBody);

          $('#tbody tr').click(function (event) {
            let id = $(this).find('td').eq(0).html();
            let name = $(this).find('td').eq(1).html();
            let price = $(this).find('td').eq(3).html();

            $('#id').val(id);
            $('#name').val(name);
            $('#price').val(price);

            $('#actionModal').modal('show');
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
  }
  function createProduct() {
    const name = $('#name').val();
    const price = $('#price').val();

    if (name !== '' && price !== '' && price > 0) {
      $.ajax({
        url: './php/products/create.php',
        method: 'POST',
        data: {
          name: name,
          price: price,
        },
        success: function (data) {
          console.log(data);
          fetchProducts();
          $('#formModal').modal('hide');
        },
      });
    } else {
      alert('Todos los campos son obligatorios');
    }
  }
  function updateProduct() {
    const id = $('#id').val();
    const name = $('#name').val();
    const price = $('#price').val();

    if (id !== '' && name !== '' && price !== '' && price > 0) {
      $.ajax({
        url: './php/products/update.php',
        method: 'POST',
        data: {
          id: id,
          name: name,
          price: price,
        },
        success: function (data) {
          console.log(data);
          fetchProducts();
          $('#formModal').modal('hide');
        },
      });
    } else {
      alert('Todos los campos son obligatorios.');
    }
  }
  function deleteProduct() {
    const id = $('#id').val();
    if (id !== '' && id > 0) {
      $.ajax({
        url: './php/products/delete.php',
        method: 'POST',
        data: {
          id: id,
        },
        success: function (data) {
          console.log(data);
          fetchProducts();
          $('#actionModal').modal('hide');
        },
      });
    } else {
      alert('Todos los campos son obligatorios.');
    }
  }

  // Implementacion
  fetchProducts();
  $('#showModalButton').click(function (event) {
    $('#form')[0].reset();
    $('#id').val('');
  });
  $('#form').submit(function (event) {
    event.preventDefault();
    if ($('#id').val() === '') {
      createProduct();
    } else {
      updateProduct();
    }
  });
  $('#deleteButton').click(function (event) {
    deleteProduct();
  });
  $('#updateButton').click(function (event) {
    $('#actionModal').modal('hide');
    $('#formModal').modal('show');
  });
  $('#donwloadPdfProductsButton').click(function (event) {
    window.open('./php/products/pdf/allProducts.php', '_blank');
  });
});
