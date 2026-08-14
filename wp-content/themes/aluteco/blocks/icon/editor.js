( function ( blocks, blockEditor, components, element, i18n ) {
	'use strict';

	const el = element.createElement;
	const Fragment = element.Fragment;
	const RawHTML = element.RawHTML;
	const registerBlockType = blocks.registerBlockType;
	const BlockControls = blockEditor.BlockControls;
	const InspectorControls = blockEditor.InspectorControls;
	const useBlockProps = blockEditor.useBlockProps;
	const PanelBody = components.PanelBody;
	const SelectControl = components.SelectControl;
	const TextControl = components.TextControl;
	const ToolbarDropdownMenu = components.ToolbarDropdownMenu;
	const __ = i18n.__;
	const icons = window.alutecoIconBlock && Array.isArray( window.alutecoIconBlock.icons )
		? window.alutecoIconBlock.icons
		: [];

	function findIcon( name ) {
		return icons.find( function ( icon ) {
			return icon.name === name;
		} ) || icons[ 0 ] || { name: '', label: '', svg: '' };
	}

	registerBlockType( 'aluteco/icon', {
		edit: function ( props ) {
			const selected = findIcon( props.attributes.icon );
			const blockProps = useBlockProps( {
				className: 'wp-block-icon aluteco-icon aluteco-icon--' + selected.name,
			} );
			const options = icons.map( function ( icon ) {
				return { label: icon.label, value: icon.name };
			} );
			const controls = icons.map( function ( icon ) {
				return {
					icon: 'star-empty',
					title: icon.label,
					onClick: function () {
						props.setAttributes( { icon: icon.name } );
					},
				};
			} );

			return el(
				Fragment,
				null,
				el(
					BlockControls,
					null,
					el( ToolbarDropdownMenu, {
						icon: 'update',
						label: __( 'Replace icon', 'aluteco' ),
						controls: controls,
					} )
				),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Icon settings', 'aluteco' ) },
						el( SelectControl, {
							label: __( 'Icon', 'aluteco' ),
							value: selected.name,
							options: options,
							onChange: function ( icon ) {
								props.setAttributes( { icon: icon } );
							},
						} ),
						el( TextControl, {
							label: __( 'Accessible label', 'aluteco' ),
							help: __( 'Leave empty when the icon is decorative.', 'aluteco' ),
							value: props.attributes.ariaLabel || '',
							onChange: function ( ariaLabel ) {
								props.setAttributes( { ariaLabel: ariaLabel } );
							},
						} )
					)
				),
				el( 'div', blockProps, el( RawHTML, null, selected.svg ) )
			);
		},
		save: function () {
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.element,
	window.wp.i18n
);
