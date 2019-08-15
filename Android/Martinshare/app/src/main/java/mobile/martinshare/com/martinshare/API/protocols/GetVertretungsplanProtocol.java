package mobile.martinshare.com.martinshare.API.protocols;

import mobile.martinshare.com.martinshare.activities.vertretungsplan.VertretungsplanData;

/**
 * Created by Modestas Valauskas on 03.06.2015.
 */
public interface GetVertretungsplanProtocol {
    public void startedGetting();
    public void wrongCredentials();
    public void success(VertretungsplanData data);
    public void noInternetConnection();
    public void keinePlaeneVorhanden();
}
