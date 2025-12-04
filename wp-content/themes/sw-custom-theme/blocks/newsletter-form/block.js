wp.blocks.registerBlockType('itg-custom-theme/newsletter-form', {
    edit: function() { // Rendered in the editor interface, not real
        return wp.element.createElement(
            'form',
            { className: 'newsletter-form' },
            wp.element.createElement('input', { 
                type: 'email', 
                placeholder: 'Enter your email',
                required: true
        }),
        wp.element.createElement('button', { type: 'submit' }, 'Subscribe')
        );
    },
    save: function() {
        return null; // still rendered via PHP on frontend
    }
});