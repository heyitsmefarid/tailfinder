#include <stdio.h>

int main() {
    int number;

    // Ask user to enter a number
    printf("Enter an integer: ");
    scanf("%d", &number);

    // Use if-else to check the number
    if (number > 0) {
        printf("The number is positive.\n");
    } 
    else if (number < 0) {
        printf("The number is negative.\n");
    } 
    else {
        printf("The number is zero.\n");
    }

    return 0;
}
