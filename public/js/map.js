(function ($) {
    "use strict";

    mapboxgl.accessToken = "pk.eyJ1IjoiaG9hbmdoYW5kbiIsImEiOiJjbTdsbTkydm8wZGpiMmxxcTdvdzVqbHd3In0.HUUli-jvI1ALTBuzSeKTpw";

    let map;
    let currentPopup;
    let popupFromClick = false; // Track if popup was created from marker click
    let activeMarker = null; // Track the currently active marker
    let lastHoveredPropertyId = null; // Track last hovered property to prevent re-animation
    let currentPopupPropertyId = null; // Track which property the current popup belongs to

    // Default properties for fallback - Vietnam coordinates
    const defaultProperties = [
        {
            id: 1,
            address: "Hà Nội, Việt Nam",
            title: "Thu mua phế liệu tại Hà Nội",
            beds: 3,
            baths: 2,
            sqft: 1600,
            coordinates: [105.8342, 21.0278],
            image: "/img/list/1.png",
        },
        {
            id: 2,
            address: "TP. Hồ Chí Minh, Việt Nam",
            title: "Thu mua phế liệu tại Sài Gòn",
            beds: 4,
            baths: 2,
            sqft: 2400,
            coordinates: [106.6297, 10.8231],
            image: "/img/list/2.png",
        }
    ];

    function initializeMap() {
        if (!document.getElementById('map')) {
            return;
        }

        map = new mapboxgl.Map({
            container: "map",
            style: "mapbox://styles/mapbox/streets-v12",
            center: [106.6297, 10.8231], // Ho Chi Minh City, Vietnam
            zoom: 11,
            attributionControl: true,
            logoPosition: 'bottom-left'
        });

        // Add controls immediately after map creation
        const nav = new mapboxgl.NavigationControl({
            showCompass: true,
            showZoom: true,
            visualizePitch: true
        });
        map.addControl(nav, "top-right");

        const fullscreen = new mapboxgl.FullscreenControl();
        map.addControl(fullscreen, "top-right");

        const geolocate = new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true,
            showUserHeading: true,
            showAccuracyCircle: true
        });
        map.addControl(geolocate, "top-right");

        map.on('load', function() {
            console.log('Map loaded successfully');

            // Use properties from template if available, otherwise use default
            const properties = window.mapProperties || defaultProperties;
            addMarkersToMap(properties);
            setupPropertyInteractions(properties);

            // Don't auto-fit bounds - keep initial view at Ho Chi Minh City
            // User can zoom to markers by clicking on them

            // FORCE controls to be visible - Multiple attempts
            function forceControlsVisible() {
                const selectors = [
                    '.mapboxgl-ctrl',
                    '.mapboxgl-ctrl-group',
                    '.mapboxgl-ctrl-top-right',
                    '.mapboxgl-ctrl-top-left',
                    '.mapboxgl-ctrl-bottom-right',
                    '.mapboxgl-ctrl-bottom-left',
                    '.mapboxgl-ctrl-logo',
                    '.mapboxgl-ctrl-attrib',
                    '.mapboxgl-ctrl-attrib-inner',
                    'button.mapboxgl-ctrl-zoom-in',
                    'button.mapboxgl-ctrl-zoom-out',
                    'button.mapboxgl-ctrl-compass',
                    'button.mapboxgl-ctrl-geolocate',
                    'button.mapboxgl-ctrl-fullscreen'
                ];

                selectors.forEach(selector => {
                    const elements = document.querySelectorAll(selector);
                    elements.forEach(el => {
                        el.style.display = 'block';
                        el.style.visibility = 'visible';
                        el.style.opacity = '1';
                        el.style.pointerEvents = 'auto';
                        el.style.zIndex = '9999';
                        console.log('Forced visible:', selector, el);
                    });
                });

                // Specifically fix control container
                const controlContainer = document.querySelector('.mapboxgl-control-container');
                if (controlContainer) {
                    controlContainer.style.position = 'absolute';
                    controlContainer.style.top = '0';
                    controlContainer.style.left = '0';
                    controlContainer.style.width = '100%';
                    controlContainer.style.height = '100%';
                    controlContainer.style.pointerEvents = 'none';
                    controlContainer.style.zIndex = '9999';
                    console.log('Fixed control container');
                }

                // Fix individual control corners - FORCE CORRECT POSITION
                const topRight = document.querySelector('.mapboxgl-ctrl-top-right');
                if (topRight) {
                    topRight.style.position = 'absolute';
                    topRight.style.top = '10px';
                    topRight.style.right = '10px';
                    topRight.style.left = 'auto';
                    topRight.style.bottom = 'auto';
                    topRight.style.pointerEvents = 'auto';
                    topRight.style.zIndex = '10000';
                    topRight.style.transform = 'none';
                    console.log('Fixed top-right position');
                }

                const bottomLeft = document.querySelector('.mapboxgl-ctrl-bottom-left');
                if (bottomLeft) {
                    bottomLeft.style.position = 'absolute';
                    bottomLeft.style.bottom = '10px';
                    bottomLeft.style.left = '10px';
                    bottomLeft.style.right = 'auto';
                    bottomLeft.style.top = 'auto';
                    bottomLeft.style.pointerEvents = 'auto';
                    bottomLeft.style.zIndex = '10000';
                    bottomLeft.style.transform = 'none';
                    console.log('Fixed bottom-left position');
                }

                const bottomRight = document.querySelector('.mapboxgl-ctrl-bottom-right');
                if (bottomRight) {
                    bottomRight.style.position = 'absolute';
                    bottomRight.style.bottom = '10px';
                    bottomRight.style.right = '10px';
                    bottomRight.style.left = 'auto';
                    bottomRight.style.top = 'auto';
                    bottomRight.style.pointerEvents = 'auto';
                    bottomRight.style.zIndex = '10000';
                    bottomRight.style.transform = 'none';
                    console.log('Fixed bottom-right position');
                }
            }

            // Call immediately
            forceControlsVisible();

            // Call again after short delays
            setTimeout(forceControlsVisible, 100);
            setTimeout(forceControlsVisible, 300);
            setTimeout(forceControlsVisible, 1000);
        });

        // Ensure map resizes correctly
        setTimeout(function() {
            map.resize();
        }, 100);
    }

    function addMarkersToMap(properties) {
        properties.forEach(function(property) {
            // Format price for display
            let priceDisplay = 'Liên hệ';
            if (property.price) {
                priceDisplay = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(property.price);
            }

            // Create custom marker element
            const el = document.createElement('div');
            el.className = 'office-marker';
            el.innerHTML = '<i class="fas fa-map-marker-alt"></i>';
            el.style.cursor = 'pointer';

            // Create marker
            const marker = new mapboxgl.Marker(el)
                .setLngLat(property.coordinates)
                .addTo(map);

            // Add click event to marker
            el.addEventListener('click', function(e) {
                e.stopPropagation();

                // Remove active class from previous marker
                if (activeMarker) {
                    activeMarker.classList.remove('active');
                }

                // Add active class to clicked marker
                el.classList.add('active');
                activeMarker = el;

                popupFromClick = true;
                createPopup(property);
                jumpToProperty(property);
            });

            // Store marker reference in property
            property.marker = marker;
            property.markerElement = el;
        });
    }

    function setupPropertyInteractions(properties) {
        $(".card-house, .card-list").on("mouseenter", function (e) {
            const propertyId = $(this).data('property-id');
            const property = properties.find(p => p.id == propertyId);

            if (!property || popupFromClick) {
                return;
            }

            // If hovering the same property, do absolutely nothing
            if (lastHoveredPropertyId === propertyId) {
                return;
            }

            // Update last hovered property
            lastHoveredPropertyId = propertyId;

            // Remove active class from all markers first
            properties.forEach(p => {
                if (p.markerElement) {
                    p.markerElement.classList.remove('active');
                }
            });

            // Add active class to current marker
            if (property.markerElement) {
                property.markerElement.classList.add('active');
            }

            // Jump to property first (instant, no jitter)
            jumpToProperty(property);

            // Then create popup (after jump complete)
            setTimeout(() => {
                createPopup(property);
            }, 50);
        });

        // Reset when mouse leaves the entire posts list container
        $("#posts-list-container").on("mouseleave", function(e) {
            if (currentPopup && !popupFromClick) {
                currentPopup.remove();
                currentPopup = null;
                currentPopupPropertyId = null;
            }

            lastHoveredPropertyId = null;

            // Remove all marker highlights if not clicked
            if (!activeMarker) {
                properties.forEach(p => {
                    if (p.markerElement) {
                        p.markerElement.classList.remove('active');
                    }
                });
            }
        });
    }

    function jumpToProperty(property) {
        if (map && property.coordinates) {
            const currentCenter = map.getCenter();
            const currentZoom = map.getZoom();
            const targetLng = property.coordinates[0];
            const targetLat = property.coordinates[1];

            // Check if already at target position (with small tolerance)
            const lngDiff = Math.abs(currentCenter.lng - targetLng);
            const latDiff = Math.abs(currentCenter.lat - targetLat);
            const zoomDiff = Math.abs(currentZoom - 15);

            // If already at target position (within 0.0001 degrees and zoom 15), don't animate
            if (lngDiff < 0.0001 && latDiff < 0.0001 && zoomDiff < 0.5) {
                return; // Already there, no need to move
            }

            // Use flyTo with smooth animation
            map.flyTo({
                center: property.coordinates,
                zoom: 15,
                speed: 1.5, // Faster than default
                curve: 1, // Less curve = more direct path
                essential: true
            });
        }
    }

    function createPopup(property) {
        // If popup exists for the same property, don't recreate
        if (currentPopup && currentPopupPropertyId === property.id) {
            return;
        }

        // Remove old popup
        if (currentPopup) {
            currentPopup.remove();
            currentPopup = null;
        }

        // Format price for display
        let priceDisplay = 'Liên hệ để biết giá';
        if (property.price) {
            priceDisplay = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(property.price);
        }

        const popupContent = `
            <div class="popup-property">
                <div class="img-style">
                    <img src="${property.image || '/img/list/1.png'}"
                         width="120" height="120" alt="popup-property"
                         style="object-fit: cover; border-radius: 8px;">
                </div>
                <div class="content">
                    <p class="text-caption-1 mb_4">${property.address || 'Địa chỉ không có'}</p>
                    <h6 class="mb_12">${property.title || 'Tiêu đề'}</h6>
                    <p class="price text-primary fw-bold mb_8">${priceDisplay}</p>
                    <ul class="info d-flex">
                        <li class="d-flex align-items-center gap_8 text_primary-color fw-6">
                            <i class="mdi mdi-scale"></i>${property.quantity || 0} kg
                        </li>
                        <li class="d-flex align-items-center gap_8 text_primary-color fw-6">
                            <i class="mdi mdi-cube"></i>${property.wasteType || 'Phế liệu'}
                        </li>
                    </ul>
                </div>
            </div>`;

        currentPopup = new mapboxgl.Popup({
            closeButton: true,
            closeOnClick: true,
            anchor: "bottom",
            offset: [0, -40],
            maxWidth: '350px'
        })
            .setLngLat(property.coordinates)
            .setHTML(popupContent)
            .addTo(map);

        // Track which property this popup belongs to
        currentPopupPropertyId = property.id;

        // Reset flag and marker style when popup is closed
        currentPopup.on('close', function() {
            popupFromClick = false;
            currentPopup = null;
            currentPopupPropertyId = null;

            // Remove active class from marker when popup closes
            if (activeMarker) {
                activeMarker.classList.remove('active');
                activeMarker = null;
            }
        });

        console.log('Popup created for:', property.title);
    }

    // Let Mapbox handle popup closing with closeOnClick option
    // No need for custom click handler that might conflict

    // Function to initialize map with properties data from backend
    window.initializeMapWithProperties = function(properties) {
        if (properties && properties.length > 0) {
            // Transform properties data to match expected format
            const transformedProperties = properties.map(function(property) {
                return {
                    id: property.id,
                    title: property.title,
                    address: property.address,
                    price: property.price,
                    quantity: property.quantity,
                    wasteType: property.wasteType,
                    coordinates: property.coordinates,
                    image: property.image || '/img/list/1.png'
                };
            });

            // Store in global variable
            window.mapProperties = transformedProperties;

            // If map already initialized, add markers
            if (map) {
                addMarkersToMap(transformedProperties);
                setupPropertyInteractions(transformedProperties);

                // Fit bounds to show all markers
                const bounds = new mapboxgl.LngLatBounds();
                transformedProperties.forEach(property => {
                    if (property.coordinates) {
                        bounds.extend(property.coordinates);
                    }
                });
                map.fitBounds(bounds, { padding: 50 });
            }
        }
    };

    // Initialize map when page loads
    $(document).ready(function() {
        if (document.getElementById('map')) {
            initializeMap();
        }
    });

})(jQuery);
