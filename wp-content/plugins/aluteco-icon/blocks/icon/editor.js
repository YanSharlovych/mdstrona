( function ( blocks, blockEditor, components, element, i18n ) {
	'use strict';

	const el = element.createElement;
	const Fragment = element.Fragment;
	const RawHTML = element.RawHTML;
	const useRef = element.useRef;
	const useState = element.useState;
	const registerBlockType = blocks.registerBlockType;
	const BlockControls = blockEditor.BlockControls;
	const InspectorControls = blockEditor.InspectorControls;
	const useBlockProps = blockEditor.useBlockProps;
	const Button = components.Button;
	const Modal = components.Modal;
	const Notice = components.Notice;
	const PanelBody = components.PanelBody;
	const SearchControl = components.SearchControl;
	const TextControl = components.TextControl;
	const ToolbarButton = components.ToolbarButton;
	const translate = i18n.__;
	const __ = function ( text ) {
		return translate( text, 'aluteco-icon' );
	};
	const icons = window.alutecoIconBlock && Array.isArray( window.alutecoIconBlock.icons )
		? window.alutecoIconBlock.icons
		: [];
	const maxSvgSize = 100 * 1024;
	const allowedElements = new Set( [
		'svg', 'g', 'path', 'polygon', 'polyline', 'line', 'rect', 'circle', 'ellipse',
	] );
	const allowedAttributes = new Set( [
		'xmlns', 'viewbox', 'width', 'height', 'class', 'fill', 'fill-rule', 'clip-rule',
		'stroke', 'stroke-width', 'stroke-linecap', 'stroke-linejoin', 'stroke-miterlimit',
		'transform', 'opacity', 'd', 'points', 'cx', 'cy', 'r', 'rx', 'ry', 'x', 'y',
		'x1', 'y1', 'x2', 'y2',
	] );

	function findIcon( name ) {
		return icons.find( function ( icon ) {
			return icon.name === name;
		} ) || icons[ 0 ] || { name: '', label: '', svg: '' };
	}

	function sanitizeSvg( markup ) {
		if ( ! markup || markup.length > maxSvgSize ) {
			throw new Error( __( 'The SVG file must be smaller than 100 KB.', 'aluteco' ) );
		}

		const documentNode = new window.DOMParser().parseFromString( markup, 'image/svg+xml' );
		const root = documentNode.documentElement;

		if ( documentNode.querySelector( 'parsererror' ) || ! root || 'svg' !== root.tagName.toLowerCase() ) {
			throw new Error( __( 'This file does not contain valid SVG markup.', 'aluteco' ) );
		}

		Array.from( root.querySelectorAll( '*' ) ).forEach( function ( node ) {
			if ( ! allowedElements.has( node.tagName.toLowerCase() ) ) {
				node.remove();
				return;
			}

			Array.from( node.attributes ).forEach( function ( attribute ) {
				if ( ! allowedAttributes.has( attribute.name.toLowerCase() ) ) {
					node.removeAttribute( attribute.name );
				}
			} );
		} );

		Array.from( root.attributes ).forEach( function ( attribute ) {
			if ( ! allowedAttributes.has( attribute.name.toLowerCase() ) ) {
				root.removeAttribute( attribute.name );
			}
		} );

		if ( ! root.getAttribute( 'viewBox' ) ) {
			const width = parseFloat( root.getAttribute( 'width' ) );
			const height = parseFloat( root.getAttribute( 'height' ) );

			if ( ! width || ! height ) {
				throw new Error( __( 'The SVG must include a viewBox or numeric width and height.', 'aluteco' ) );
			}

			root.setAttribute( 'viewBox', '0 0 ' + width + ' ' + height );
		}

		if ( ! root.querySelector( 'path, polygon, polyline, line, rect, circle, ellipse' ) ) {
			throw new Error( __( 'The SVG does not contain a supported icon shape.', 'aluteco' ) );
		}

		root.setAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );
		return new window.XMLSerializer().serializeToString( root );
	}

	function IconLibrary( props ) {
		const fileInput = useRef( null );
		const searchState = useState( '' );
		const search = searchState[ 0 ];
		const setSearch = searchState[ 1 ];
		const errorState = useState( '' );
		const uploadError = errorState[ 0 ];
		const setUploadError = errorState[ 1 ];
		const query = search.trim().toLowerCase();
		const visibleIcons = icons.filter( function ( icon ) {
			return ! query || icon.label.toLowerCase().includes( query ) || icon.name.includes( query );
		} );

		function uploadSvg( event ) {
			const file = event.target.files && event.target.files[ 0 ];
			event.target.value = '';
			setUploadError( '' );

			if ( ! file ) {
				return;
			}

			if ( ! /\.svg$/i.test( file.name ) || ( file.type && 'image/svg+xml' !== file.type ) ) {
				setUploadError( __( 'Choose a file in SVG format.', 'aluteco' ) );
				return;
			}

			if ( file.size > maxSvgSize ) {
				setUploadError( __( 'The SVG file must be smaller than 100 KB.', 'aluteco' ) );
				return;
			}

			const reader = new window.FileReader();
			reader.onerror = function () {
				setUploadError( __( 'The SVG file could not be read.', 'aluteco' ) );
			};
			reader.onload = function () {
				try {
					props.onUpload( sanitizeSvg( String( reader.result || '' ) ), file.name );
				} catch ( error ) {
					setUploadError( error.message || __( 'The SVG file is not supported.', 'aluteco' ) );
				}
			};
			reader.readAsText( file );
		}

		return el(
			Modal,
			{
				title: __( 'Choose an icon', 'aluteco' ),
				className: 'aluteco-icon-library-modal',
				onRequestClose: props.onClose,
			},
			el(
				'div',
				{ className: 'aluteco-icon-library-toolbar' },
				el( SearchControl, {
					label: __( 'Search icons', 'aluteco' ),
					placeholder: __( 'Search icons', 'aluteco' ),
					value: search,
					onChange: setSearch,
				} ),
				el( Button, {
					variant: 'secondary',
					icon: 'upload',
					onClick: function () { fileInput.current.click(); },
				}, __( 'Upload SVG', 'aluteco' ) ),
				el( 'input', {
					ref: fileInput,
					type: 'file',
					accept: '.svg,image/svg+xml',
					className: 'aluteco-icon-library-file',
					onChange: uploadSvg,
				} )
			),
			uploadError && el( Notice, {
				status: 'error',
				isDismissible: true,
				onRemove: function () { setUploadError( '' ); },
			}, uploadError ),
			props.customSvg && el(
				'div',
				{ className: 'aluteco-icon-library-section' },
				el( 'p', { className: 'aluteco-icon-library-heading' }, __( 'Uploaded icon', 'aluteco' ) ),
				el(
					'button',
					{
						type: 'button',
						className: 'aluteco-icon-library-item' + ( 'custom' === props.selected ? ' is-selected' : '' ),
						'aria-pressed': 'custom' === props.selected,
						onClick: props.onChooseCustom,
					},
					el( 'span', { className: 'aluteco-icon-library-preview' }, el( RawHTML, null, props.customSvg ) ),
					el( 'span', { className: 'aluteco-icon-library-label' }, props.customFileName || __( 'Custom SVG', 'aluteco' ) )
				)
			),
			el(
				'div',
				{ className: 'aluteco-icon-library-section' },
				el( 'p', { className: 'aluteco-icon-library-heading' }, __( 'ALUTECO icons', 'aluteco' ) ),
				visibleIcons.length
					? el(
						'div',
						{ className: 'aluteco-icon-library-grid' },
						visibleIcons.map( function ( icon ) {
							const selected = icon.name === props.selected;
							return el(
								'button',
								{
									key: icon.name,
									type: 'button',
									className: 'aluteco-icon-library-item' + ( selected ? ' is-selected' : '' ),
									'aria-pressed': selected,
									onClick: function () { props.onChoose( icon.name ); },
								},
								el( 'span', { className: 'aluteco-icon-library-preview' }, el( RawHTML, null, icon.svg ) ),
								el( 'span', { className: 'aluteco-icon-library-label' }, icon.label )
							);
						} )
					)
					: el( 'p', { className: 'aluteco-icon-library-empty' }, __( 'No icons match your search.', 'aluteco' ) )
			)
		);
	}

	registerBlockType( 'aluteco/icon', {
		edit: function ( props ) {
			const modalState = useState( false );
			const isLibraryOpen = modalState[ 0 ];
			const setLibraryOpen = modalState[ 1 ];
			let sanitizedCustomSvg = '';

			if ( props.attributes.customSvg ) {
				try {
					sanitizedCustomSvg = sanitizeSvg( props.attributes.customSvg );
				} catch ( error ) {
					sanitizedCustomSvg = '';
				}
			}

			const isCustom = 'custom' === props.attributes.icon && !! sanitizedCustomSvg;
			const selected = isCustom
				? { name: 'custom', label: props.attributes.customFileName || __( 'Custom SVG', 'aluteco' ), svg: sanitizedCustomSvg }
				: findIcon( props.attributes.icon );
			const blockProps = useBlockProps( {
				className: 'aluteco-icon aluteco-icon--' + selected.name,
			} );

			function chooseIcon( icon ) {
				props.setAttributes( { icon: icon } );
				setLibraryOpen( false );
			}

			function uploadIcon( svg, fileName ) {
				props.setAttributes( {
					icon: 'custom',
					customSvg: svg,
					customFileName: fileName,
				} );
				setLibraryOpen( false );
			}

			return el(
				Fragment,
				null,
				el(
					BlockControls,
					null,
					el( ToolbarButton, {
						icon: 'update',
						label: __( 'Replace icon', 'aluteco' ),
						onClick: function () { setLibraryOpen( true ); },
					} )
				),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Icon settings', 'aluteco' ) },
						el( Button, {
							variant: 'secondary',
							className: 'aluteco-icon-library-open',
							onClick: function () { setLibraryOpen( true ); },
						}, __( 'Browse icon library', 'aluteco' ) ),
						el( TextControl, {
							label: __( 'Accessible label', 'aluteco' ),
							help: __( 'Leave empty when the icon is decorative.', 'aluteco' ),
							value: props.attributes.ariaLabel || '',
							onChange: function ( ariaLabel ) { props.setAttributes( { ariaLabel: ariaLabel } ); },
						} )
					)
				),
				el( 'div', blockProps, el( RawHTML, null, selected.svg ) ),
				isLibraryOpen && el( IconLibrary, {
					selected: selected.name,
					customSvg: sanitizedCustomSvg,
					customFileName: props.attributes.customFileName,
					onChoose: chooseIcon,
					onChooseCustom: function () { chooseIcon( 'custom' ); },
					onUpload: uploadIcon,
					onClose: function () { setLibraryOpen( false ); },
				} )
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
