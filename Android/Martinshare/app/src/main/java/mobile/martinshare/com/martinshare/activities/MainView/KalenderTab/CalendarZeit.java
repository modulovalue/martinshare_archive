package mobile.martinshare.com.martinshare.activities.MainView.KalenderTab;

import org.joda.time.DateTime;

public class CalendarZeit {

    public DateTime dt;
    private static CalendarZeit instance;

    private CalendarZeit() {
        dt = DateTime.now();
    }

    public static CalendarZeit getInstance() {
        if(instance == null) {
            instance = new CalendarZeit();
        }

        return instance;
    }

    public void resetTime() {
        this.dt = DateTime.now();
    }

    public void refreshMonth(int month) {
        dt = dt.plusMonths(month);
    }

}
