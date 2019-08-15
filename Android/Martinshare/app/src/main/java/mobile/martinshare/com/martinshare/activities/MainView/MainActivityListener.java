package mobile.martinshare.com.martinshare.activities.MainView;

import mobile.martinshare.com.martinshare.API.POJO.EintraegeList;

/**
 * Created by Modestas Valauskas on 10.02.2016.
 */
public interface MainActivityListener {
    void einträgeStartedLoading();
    void einträgeLoadFailed();
    void einträgeLoadFailedNoInternet();
    void einträgeLoaded(EintraegeList eintraegeList);
    void einträgeLoadedNotChanged();
}
