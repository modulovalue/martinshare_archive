package mobile.martinshare.com.martinshare.activities.MainView;

import android.support.v7.app.ActionBar;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;

public interface MenuOptionsDesigner {
    
    public void createOptionsMenu(ActionBar actionBar, Menu menu, MenuInflater menuInflater);
    public void optionsItemSelected(MenuItem menuItem);
}
