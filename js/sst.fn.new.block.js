'use strict';
var _lodash = lodash, assign = _lodash.assign;
var __ = wp.i18n.__;
var Fragment = wp.element.Fragment;
var addFilter = wp.hooks.addFilter;
var PanelBody = wp.components.PanelBody;
var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
var InspectorControls = wp.editor.InspectorControls;
var _components = wp.components;
var _compose = wp.compose;

var MyTextControl = (0, _compose.withState)({
    className: ''
})(function (_ref) {
    var className = 'button button-primary button-large sba_btnCheck',
        setState = _ref.setState;
    return React.createElement(
        _components.Circle,
        {
            class: className
        },
        __('Check Plagiarism')
    );
});

var addMyCustomBlockControls = createHigherOrderComponent(function (BlockEdit) {
    return function (props) {

        if (isValidBlockType(props.name) && props.isSelected) {
            return React.createElement(
                Fragment,
                null,
                React.createElement(BlockEdit, props),
                React.createElement(
                    InspectorControls,
                    null,
                    React.createElement(
                        PanelBody,
                        { title: __('Plagiarism checker by sst') },
                        React.createElement(MyTextControl, {
                            value: props.attributes.scheduledStart || ''
                        })
                    )
                )
            );
        }
        return React.createElement(BlockEdit, props);
    };
}, 'addMyCustomBlockControls');

addFilter('editor.BlockEdit', 'plagiarism-checker-by-sst/sst-control', addMyCustomBlockControls);

function isValidBlockType(name) {
    var validBlockTypes = ['core/paragraph', 'core/image', 'core/heading'];
    return validBlockTypes.includes(name);
}

function addAttribute(settings) {

    // If this is a valid block
    if (isValidBlockType(settings.name)) {

        // Use Lodash's assign to gracefully handle if attributes are undefined
        settings.attributes = assign(settings.attributes, {
            scheduledStart: {
                type: 'string'
            }
        });
    }

    return settings;
}

function addSaveProps(extraProps, blockType, attributes) {
    if (isValidBlockType(blockType.name)) {
        extraProps.scheduledStart = attributes.scheduledStart;
    }
    return extraProps;
}

addFilter('blocks.registerBlockType', 'plagiarism-checker-by-sst/add-attr', addAttribute);
addFilter('blocks.getSaveContent.extraProps', 'plagiarism-checker-by-sst/add-props', addSaveProps);