// Variables for collection IDs
var userId = $user['id'];
var dreamId = $dream['id'];

// DELETE Entries from Database
// Function that removes the user id from the database
function confirmDeleteu(userId) {
	var deleteUser = confirm('Are you sure you want to delete this User?');
	if(deleteUser){
		window.location.href = 'deleteUser.php?id='+userId;
		}
		return;
}

// Function that removes the dream id from the database
function confirmDeleted(dreamId) {
	var deleteDream = confirm('Are you sure you want to delete this Dream?');
	if(deleteDream){
		window.location.href = 'deleteDream.php?id='+dreamId;
		}
		return;
}