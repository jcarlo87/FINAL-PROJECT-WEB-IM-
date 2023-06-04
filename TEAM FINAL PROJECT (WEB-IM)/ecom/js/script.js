//functions for opening and closing a form
function openForm(id) {
    document.getElementById(id).style.display = "block";
}
function closeForm(id) {
    document.getElementById(id).style.display = "none";
}

//tohgle show, hide password
function toggle(id) {
    var x = document.getElementById(id);
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

//toggle to show menu
function toggleMenu() {
  var element = document.getElementById("account-box");
  if (element.style.display === "none") {
      element.style.display = "block";
  } else {
      element.style.display = "none";
  }
}

  //validate add product from 
  function validateForm() {
    var name = document.getElementById("product_name").value;
    var description = document.getElementById("product_description").value;
    var image = document.getElementById("image").value;
    var category = document.getElementById("product_category").value;
    var supplier = document.getElementById("product_supplier").value;
    var price = document.getElementById("product_price").value;

    if (name === "" || description === "" || image === "" || category === "" || supplier === "" || price === "") {
        alert("Please fill in all fields.");
        return false;
    }
    else if(price < 0) {
      alert("Invalid Price amount!");
      return false;
    }
    return true;
}

//check if number is not negative
//it is used in checking inventory quantity
function validateNumber(id) {
  var number = document.getElementById(id).value;

  if(number < 0) {
    alert("Invalid number!");
    return false;
  } return true;
}

//category filter
const categoryButtons = document.querySelectorAll('.category-btn button');
const products = document.querySelectorAll('.product-box-container');

function filterProducts(category) {
    products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
            product.style.display = '';
        } else {
            product.style.display = 'none';
        }
    });
}

categoryButtons.forEach(button => {
    button.addEventListener('click', () => {
        const category = button.dataset.category;
        filterProducts(category);
    });
});




