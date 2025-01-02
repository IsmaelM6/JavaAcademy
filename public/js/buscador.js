document.getElementById('searchIcon').addEventListener('click', function() {
    var searchBar = document.getElementById('searchBar');
    searchBar.style.display = (searchBar.style.display === 'none' || searchBar.style.display === '') ? 'block' : 'none';
});