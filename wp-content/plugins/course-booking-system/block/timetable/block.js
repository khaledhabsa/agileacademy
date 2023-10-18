( function ( blocks, element, serverSideRender, blockEditor, components, i18n ) {
	var __ = i18n.__,
		el = element.createElement,
		registerBlockType = blocks.registerBlockType,
		ServerSideRender = serverSideRender,
		useBlockProps = blockEditor.useBlockProps,
		InspectorControls = blockEditor.InspectorControls,
		BlockControls = element.BlockControls,
		PanelBody = components.PanelBody,
		SelectControl = components.SelectControl;

	registerBlockType( 'course-booking-system/timetable', {
		apiVersion: 3,
		title: __( 'Timetable', 'course-booking-system' ),
		description: __( 'Display your timetable with courses on the page.', 'course-booking-system' ),
		icon: 'editor-table',
		category: 'widgets',
		supports: {
			align: true,
			alignWide: true
		},
		attributes: {
			category: {
				type: 'array',
				default: []
			},
			design: {
				type: 'string',
				default: 'default'
			}
		},

		edit: function ( props ) {
			var blockProps = useBlockProps();

			// Get course categotory taxonomy for select options
			const taxonomies = wp.data.select( 'core' ).getEntityRecords( 'taxonomy', 'course_category' );
			const options = [];
			jQuery.each( taxonomies, function( key, val ) {
				options.push( { value: val.id, label: val.name } );
			});

			return [
				el( 'div', blockProps,
					el( ServerSideRender, {
						block: 'course-booking-system/timetable',
						attributes: props.attributes,
					} )
				),
				el( InspectorControls, { key: 'setting' },
					el( PanelBody, {
						title: __( 'Timetable Attributes', 'course-booking-system' ),
						className: 'block-timetable-attributes',
						initialOpen: true
					},
					el( 'p', {}, __( 'Configure the timetable according to your needs.', 'course-booking-system' ) ),
					el( SelectControl, {
						label: __( 'Course Category', 'course-booking-system' ),
						value: props.attributes.category,
						multiple: true,
						options: options,
						onChange: function ( newCategory ) {
							props.setAttributes( { category: newCategory } )
						}
					}),
					el( SelectControl, {
						label: __( 'Timetable Design', 'course-booking-system' ),
						value: props.attributes.design,
						options: [
							{ value: '', label: __( 'Use selected design from plugin settings', 'course-booking-system' ) },
							{ value: 'default', label: __( 'Default', 'course-booking-system' ) },
							{ value: 'divided', label: __( 'Divided', 'course-booking-system' ) },
							{ value: 'list', label: __( 'List', 'course-booking-system' ) }
						],
						onChange: function ( newDesign ) {
							props.setAttributes( { design: newDesign } )
						}
					}))
				)
			]
		},
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.serverSideRender,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n
);
