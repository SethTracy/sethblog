def main():
    student_name = input("Enter student's name: ")
    print()
    num_subjects = int(input("Enter the number of subjects: "))
    print()

    subjects = []
    grades = []

    for i in range(num_subjects):
        subject_name = input(f"Enter the name of subject {i + 1}: ")
        subjects.append(subject_name)
    print()
    total_grades = 0
    for subject in subjects:
        grade = float(input(f"Enter the grade for {subject}: "))
        grades.append(grade)
        total_grades += grade
    average_grade = total_grades / num_subjects

    print("\nSummary:")
    print("Student Name: {}".format(student_name))
    print("Subjects and Grades:")
    for i in range(num_subjects):
        print(f"{subjects[i]}: {grades[i]:.2f}")
    print("Total Grades: {:.2f}".format(total_grades))
    print("Average Grade: {:.2f}".format(average_grade))

    if average_grade >= 75:
        status = "Passed"
    else:
        status = "Failed"

    result_message = "The student has " + status + "."
    print(result_message)

    rounded_average = round(average_grade)
    print(f"Rounded Average Grade: {rounded_average}")

    print("\nWould you like to enter grades for another student?")
    while True:
        continue_input = input("Enter 'yes' to continue or 'no' to exit: ").lower()
        if continue_input == 'yes':
            print("\nEntering new student data...\n")
            main()
            break
        elif continue_input == 'no':
            print("\nThank you for using the Student Grading System. Goodbye!")
            break
        else:
            print("Invalid input. Please enter 'yes' or 'no'.")

main()