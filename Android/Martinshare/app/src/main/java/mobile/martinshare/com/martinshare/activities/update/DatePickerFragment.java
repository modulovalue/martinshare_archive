package mobile.martinshare.com.martinshare.activities.update;

import android.app.Activity;
import android.app.DatePickerDialog;
import android.app.Dialog;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.app.DialogFragment;
import android.widget.DatePicker;

public class DatePickerFragment extends DialogFragment implements DatePickerDialog.OnDateSetListener {

    private int year, month, day;
    DateFragment dateFragmentComander;

    public static DatePickerFragment newInstance(int day, int month, int year) {
        DatePickerFragment myFragment = new DatePickerFragment();
        Bundle args = new Bundle();
        args.putInt("day", day);
        args.putInt("month", month);
        args.putInt("year", year);
        myFragment.setArguments(args);
        return myFragment;
    }

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {

        DatePickerDialog datePickerDialog = new DatePickerDialog(getActivity(), this, year, month, day);
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
           datePickerDialog.getDatePicker().setFirstDayOfWeek(2);
        }
        datePickerDialog.setCanceledOnTouchOutside(true);
        datePickerDialog.show();
        return datePickerDialog;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        day = getArguments().getInt("day");
        month = getArguments().getInt("month");
        year = getArguments().getInt("year");
    }

    public interface DateFragment {
        void setDate(int day, int month, int year);
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try{
            dateFragmentComander = (DateFragment) activity;
        } catch(ClassCastException e) {

        }
    }

    public void onDateSet(DatePicker view, int yearn, int monthn, int dayn) {
        dateFragmentComander.setDate(dayn, monthn + 1, yearn);
    }
}