// ============= Card Slider Home page =============




// ============== Behavior for adding new Ingredients in Add Modal ============== 
document.getElementById('addButton').addEventListener('click', addIngredient);
function addIngredient() {
    const ingredientsContainer = document.getElementById('ingredientsContainer');

    // Create a new input group for the ingredient
    const inputGroup = document.createElement('div');
    inputGroup.className = 'input-group mb-2';

    // Create the input field for the ingredient
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control';
    input.name = 'dish_ingredients[]';
    input.placeholder = 'Add an ingredient';

    // Create the remove button
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-danger removeButton';
    removeButton.textContent = 'Remove';
    removeButton.onclick = function() {
        inputGroup.remove();
    };

    // Append the input field and remove button to the input group
    inputGroup.appendChild(input);
    inputGroup.appendChild(removeButton);

    // Append the input group to the ingredients container
    ingredientsContainer.appendChild(inputGroup);
}

// Event delegation to handle remove buttons
document.getElementById('ingredientsContainer').addEventListener('click', function(event) {
    if (event.target.classList.contains('removeButton')) {
        event.target.parentElement.remove();
    }
});


// ============== Behavior for editing the added Ingredients in Edit Modal ==============  

function populateModal(data) {
    const edit_ingredientsContainer = document.getElementById('edit_ingredientsContainer');
    edit_ingredientsContainer.innerHTML = ''; // Clear existing content

    // Iterate over fetched ingredients and add each as a separate input field
    data.ingredients.forEach(ingredient => {
        edit_Ingredient(ingredient);  // Use consistent function name
    });
}

// Event listener for "Add Ingredient" button
document.getElementById('editButton').addEventListener('click', function() {
    edit_Ingredient();  // Call the function with an empty string to add a new empty input
});

function edit_Ingredient(ingredientText = '') {
    const edit_ingredientsContainer = document.getElementById('edit_ingredientsContainer');

    // Create a new input group
    const inputGroup = document.createElement('div');
    inputGroup.className = 'input-group mb-2';

    // Create the input field
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control';
    input.name = 'edit_dish_ingredients[]';
    input.placeholder = 'Add an ingredient';
    input.value = ingredientText;  // Set the fetched ingredient or leave empty for new ones

    // Create the remove button
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-danger';
    removeButton.textContent = 'Remove';
    removeButton.onclick = function() {
        inputGroup.remove();  // Remove the input group on click
    };

    // Append input and remove button to the input group
    inputGroup.appendChild(input);
    inputGroup.appendChild(removeButton);

    // Append the input group to the ingredients container
    edit_ingredientsContainer.appendChild(inputGroup);
}





// Edit Image on profile page
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('profilePic');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Ajax Call Below
// ============== Edit Modal on Profile Page ==================

// This on Retreive only
$(document).ready(function() {
    $('button[data-bs-target="#edit_post_dish"]').on('click', function() {
        var userId = $(this).data('user-id');
        var dishName = $(this).data('dish-name');

        //AJAX request to fetch data
        $.ajax({
            url: 'process/fetch_dish_data.php', //PHP url script to fetch the data
            type:'GET',
            data:{
                user_id: userId,
                dish_name: dishName
            },
            success: function(response){
                //This function runs if the request is successful

                //Parse the JSON
                var data = JSON.parse(response);

                // Pupulate the modal fields with the fetched data
                $('#edit_id').val(data.id);
                // $('#edit_user_id').val(data.user_id);
                $('#edit_dish_name').val(data.dish_name);
                $('#edit_cuisine_type').val(data.cuisine_type);
                $('#edit_dish_ingredients').val(data.ingredients);
                $('#edit_dish_directions').val(data.directions);
                $('#edit_dish_prep_time').val(data.prep_time);

                // Clear existing ingredients and handle them dynamically
                const edit_ingredientsContainer = document.getElementById('edit_ingredientsContainer');
                edit_ingredientsContainer.innerHTML = ''; // Clear existing ingredients
                
                // Loop through each ingredient and add an input field for it
                data.ingredients.forEach(ingredient => {
                    edit_Ingredient(ingredient);  // Use the correct function name
                });
                                        
                console.log(response);
            },
            error: function(xhr, status, error){
                console.error('Error fetching data: ', error);
            }
        })
    });
});

// This on saving edited data on forms
$(document).ready(function() {
    $('#editDishForm').on('submit', function(e) {
        e.preventDefault();  // Prevent the default form submission

        // Serialize form data
        var formData = new FormData(this);

        // Make the AJAX call to save data
        $.ajax({
            url: 'process/save_dish_data.php',  // Your PHP script to save the data
            type: 'POST',
            data: formData,
            processData: false,  // Required to send FormData
            contentType: false,  // Required to send FormData
            success: function(response) {
                // Handle success
                $('#edit_post_dish').modal('hide');
                //console.log(response);
                location.reload();   //Remove this if you want to see console log when saving on edit dish
                
            },
            error: function(xhr, status, error) {
                console.error('Error saving data: ', xhr.responseText);  // Log the server's response
                alert('Error: ' + xhr.responseText);
                
            }
        });
    });
});
