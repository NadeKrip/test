function download_file(name, url) {
    const link = document.createElement('a');

    link.setAttribute('href', url);
    link.setAttribute('webkitdirectory', '');
    link.setAttribute('download', name);
    link.addEventListener('click', () => {
        setTimeout(() => {
            URL.revokeObjectURL(url);
        });
    });

    link.click();
}

$(function(){
    try {
        $('.js-chosen').chosen({
            width: '100%',
            no_results_text: 'Совпадений не найдено',
            placeholder_text_single: 'Выберите сотрудника'
        });
    }
    catch (error){
        console.log('Chosen dont work - ' + error);
    }

})