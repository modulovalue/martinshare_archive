package mobile.martinshare.com.martinshare.activities.MainView.KalenderTab;

import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.daimajia.androidanimations.library.Techniques;
import com.daimajia.androidanimations.library.YoYo;

import org.joda.time.DateTime;

import mobile.martinshare.com.martinshare.R;

public class CalendarDayFragment extends Fragment {

    public TextView dayNumber;
    public ImageView markImage;
    public LinearLayout daylayout;

    private boolean thismonth = false;
    private boolean prevmonth = false;
    private boolean nextmonth = false;

    public DateTime date = null;

    CalendarDayInterface calendarDayInterface;


    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        final View view = inflater.inflate(R.layout.calendar_day_layout, container, false);

        calendarDayInterface = (CalendarDayInterface) getParentFragment();

        dayNumber = (TextView) view.findViewById(R.id.dayNumber);
        markImage = (ImageView) view.findViewById(R.id.markImage);
        daylayout = (LinearLayout) view.findViewById(R.id.daylinlayout);

        daylayout.setOnClickListener(
                new View.OnClickListener() {
                    public void onClick(View v) {
                        calendarDayInterface.handleDayClick(date, CalendarDayFragment.this, false);
                    }
                }
        );

        return view;
    }

    public void resetDay(DateTime selectedDate) {
        if(this.date.withTime(0, 0, 0, 0).isEqual(selectedDate.withTime(0, 0, 0, 0))) {
            setSelectedStyle();
        } else if (calendarDayInterface.isToday(date)) {
            setTodayStyle();
        }  else {
            setNeutralStyle();
        }
    }

    public void setSelectedStyle() {

        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                YoYo.with(Techniques.Tada).duration(200).playOn(dayNumber);
                daylayout.setBackgroundColor(getResources().getColor(R.color.grey_500));
                dayNumber.setTextColor(Color.WHITE);
            }
        });

    }

    public void setNeutralStyle() {
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                daylayout.setBackgroundColor(getResources().getColor(thismonth ? R.color.grey_white_1000 : R.color.grey_300));
                dayNumber.setTypeface(null, Typeface.NORMAL);
                dayNumber.setTextColor(Color.BLACK);
            }
        });
    }

    public void setTodayStyle() {
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                daylayout.setBackgroundColor(Color.YELLOW);
                dayNumber.setTypeface(null, Typeface.NORMAL);
                dayNumber.setTextColor(Color.BLACK);
            }
        });
    }

    public void setText(final String neuText) {
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                dayNumber.setText(neuText);
            }
        });
    }

    public void setDate(final DateTime date, boolean thismonth, boolean nextmonth, boolean prevmonth) {
        this.thismonth = thismonth;
        this.prevmonth = prevmonth;
        this.nextmonth = nextmonth;
        this.date = date;
        getActivity().runOnUiThread(new Runnable() {
            @Override
            public void run() {
                markImage.setImageDrawable(calendarDayInterface.markImage(date));
                markImage.setAlpha(0.79f);
            }
        });
    }

}
