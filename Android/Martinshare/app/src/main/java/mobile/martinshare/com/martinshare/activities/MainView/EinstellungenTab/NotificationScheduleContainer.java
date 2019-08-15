package mobile.martinshare.com.martinshare.activities.MainView.EinstellungenTab;

public class NotificationScheduleContainer {
    public int countA = 0, countH = 0, countS = 0;
    public NotificationScheduleContainer addToNSC(String typ) {
        if(typ.equals("a")) {
            countA++;
        } else if(typ.equals("h")) {
            countH++;
        } else if(typ.equals("s")) {
            countS++;
        }
        return this;
    }
}
