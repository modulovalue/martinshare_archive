package mobile.martinshare.com.martinshare.API.protocols;

/**
 * Created by Modestas Valauskas on 06.06.2015.
 */
public interface FeedbackProtocol {
    public void startedFeedback();
    public void feedbacksent();
    public void isNotLoggedIn();
    public void noInternet();
    public void unknownError(final String error);
}
