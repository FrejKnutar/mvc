if (typeof Facebook === 'undefined') {
    Facebook = function() {
        var id,
            accessToken,
            getAlbums = function() {
                FB.api(
                    "/v1.0/" + facebookId,
                    function (response) {
                      if (response && !response.error) {
                        /* handle the result */
                      }
                    }
                );
            },
            getEvents = function() {

            };
        return {
            id: id,
            accessToken: accessToken,
            getAlbums: getAlbums,
            getEvents: getEvents
        };
        console.log(getAlbums());
    };
}