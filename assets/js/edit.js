(function () {
    'use strict';

    function reindex(container) {
        container.querySelectorAll('.repeat-item').forEach(function (item, index) {
            item.querySelectorAll('[data-field]').forEach(function (input) {
                var group = container.dataset.group;
                input.name = group + '[' + index + '][' + input.dataset.field + ']';
            });
        });
    }

    document.querySelectorAll('[data-repeat-container]').forEach(function (container) {
        container.addEventListener('click', function (event) {
            var removeButton = event.target.closest('[data-remove-item]');
            if (!removeButton) return;

            if (container.querySelectorAll('.repeat-item').length <= 1) {
                window.alert('Minimal harus ada satu data.');
                return;
            }

            removeButton.closest('.repeat-item').remove();
            reindex(container);
        });
    });

    document.querySelectorAll('[data-add-target]').forEach(function (button) {
        button.addEventListener('click', function () {
            var target = document.querySelector(button.dataset.addTarget);
            var template = document.querySelector(button.dataset.template);
            if (!target || !template) return;

            target.appendChild(template.content.cloneNode(true));
            reindex(target);
        });
    });
})();
