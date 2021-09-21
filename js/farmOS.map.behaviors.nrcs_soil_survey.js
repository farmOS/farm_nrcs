(function () {
  farmOS.map.behaviors.nrcs_soil_survey = {
    attach: function (instance) {
      var opts = {
        title: 'NRCS Soil Survey',
        url: 'https://sdmdataaccess.nrcs.usda.gov/Spatial/SDM.wms',
        params: {
          LAYERS: 'MapunitPoly',
          VERSION: '1.1.1',
        },
        visible: false,
      };
      instance.addLayer('wms', opts);
    }
  };
}());
