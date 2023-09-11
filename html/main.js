        function editTodo(id) {
            window.location.href = "edit.php?id=" + id;
        }
        function deleteTodo(id) {
            var confirmDelete = confirm("Are you sure you want to delete this todo?");
            if (confirmDelete) {
                window.location.href = "?delete=" + id;
            }
        }
        // Removed sortTable function
