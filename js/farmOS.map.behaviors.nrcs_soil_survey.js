(function () {
  farmOS.map.behaviors.nrcs_soil_survey = {
    attach: function (instance) {
      // Set initial visibility based on settings (defaults to false).
      var visible = false;
      var settings = instance.farmMapSettings;
      if (settings.behaviors && settings.behaviors.nrcs_soil_survey && settings.behaviors.nrcs_soil_survey.visible) {
        visible = settings.behaviors.nrcs_soil_survey.visible;
      }
      var opts = {
        title: 'NRCS Soil Survey',
        url: 'https://sdmdataaccess.nrcs.usda.gov/Spatial/SDM.wms',
        params: {
          LAYERS: 'MapunitPoly',
          VERSION: '1.1.1',
        },
        visible: visible,
      };
      instance.addLayer('wms', opts);
    }
  };
}());
